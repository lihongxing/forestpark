<?php

namespace app\modules\rbac\controllers;

use app\common\base\AdminbaseController;
use Yii;
use app\modules\rbac\models\Assignment;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\rbac\models\form\NodeForm;
use app\modules\rbac\models\searchs\User as UserSearch;
use yii\data\Pagination;
/**
 * AssignmentController implements the CRUD actions for Assignment model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class AssignmentController extends AdminbaseController
{
    public $userClassName;
    public $idField = 'id';
    public $usernameField = 'username';
    public $fullnameField;
    public $searchClass;
    public $extraColumns = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->userClassName === null) {
            $this->userClassName = Yii::$app->getUser()->identityClass;
            $this->userClassName = $this->userClassName ? : 'app\modules\rbac\models\User';
        }
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'assign' => ['post'],
                    'assign' => ['post'],
                    'revoke' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Assignment models.
     * @return mixed
     */
    public function actionIndex()
    {

        $GET = yii::$app->request->get();
        $searchModel = new UserSearch();
        $searchModelquery = $searchModel->find();
        if(!empty($GET['name'])){
            $searchModelquery = $searchModelquery
                ->where(['or',
                    ['like', 'username', $GET['name']],
                    ['like', 'mobile', $GET['name']],
                    ['like', 'email', $GET['name']]
                ]);
        }
        if(!empty($GET['followed'])){
            $searchModelquery = $searchModelquery
                ->andWhere(['status' => $GET['followed']]);
        }
        if(!empty($GET['groupid'])){
            $searchModelquery = $searchModelquery
                ->andWhere(['role' => $GET['groupid']]);
        }
        if($GET['searchtime'] == 1){
            $time = $GET['time'];
            if(!empty($time)){
                $searchModelquery = $searchModelquery
                    ->andWhere(['between','createtime', strtotime($time['start']),strtotime($time['end'])]);
            }
        }
        $pages = new Pagination(['totalCount' => $searchModelquery->count(), 'pageSize' => '10']);
        $users = $searchModelquery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        //获取所有用户角色
        $NodeForm = new NodeForm();
        $ids = $NodeForm->getPermissionsids(1);
        return $this->render('index', [
            'users' => $users,
            'pages' => $pages,
            'ids' => $ids,
            'GET' => $GET
        ]);
    }

    /**
     * Displays a single Assignment model.
     * @param  integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
                'model' => $model,
                'idField' => $this->idField,
                'usernameField' => $this->usernameField,
                'fullnameField' => $this->fullnameField,
        ]);
    }

    /**
     * Assign items
     * @param string $id
     * @return array
     */
    public function actionAssign($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = new Assignment($id);
        $success = $model->assign($items);
        Yii::$app->getResponse()->format = 'json';
        $items = $model->getItems();
        $usermodel = new UserSearch();
        $keys = array_keys($items['assigned']);
        $attributes = array(
            'role' => json_encode($keys)
        );
        $condition = 'id=:id';
        $params = array(
            ':id' => $id
        );
        $usermodel->updateAll($attributes, $condition, $params);
        return array_merge($items, ['success' => $success]);
    }

    /**
     * Assign items
     * @param string $id
     * @return array
     */
    public function actionRemove($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = new Assignment($id);
        $success = $model->revoke($items);
        Yii::$app->getResponse()->format = 'json';
        $items = $model->getItems();
        $usermodel = new UserSearch();
        $keys = array_keys($items['assigned']);
        $attributes = array(
            'role' => json_encode($keys)
        );
        $condition = 'id=:id';
        $params = array(
            ':id' => $id
        );
        $usermodel->updateAll($attributes, $condition, $params);
        return array_merge($items, ['success' => $success]);
    }

    /**
     * Finds the Assignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param  integer $id
     * @return Assignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $class = $this->userClassName;
        if (($user = $class::findIdentity($id)) !== null) {
            return new Assignment($id, $user);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
