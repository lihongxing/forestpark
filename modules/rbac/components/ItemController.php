<?php

namespace app\modules\rbac\components;

use app\common\base\AdminbaseController;
use Yii;
use app\modules\rbac\models\AuthItem;
use app\modules\rbac\models\form\NodeForm;
use app\modules\rbac\models\searchs\AuthItem as AuthItemSearch;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\base\NotSupportedException;
use yii\filters\VerbFilter;
use yii\rbac\Item;

/**
 * AuthItemController implements the CRUD actions for AuthItem model.
 *
 * @property integer $type
 * @property array $labels
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class ItemController extends AdminbaseController
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'assign' => ['post'],
                    'remove' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex(){
        $name = yii::$app->request->get('name');
        $time = yii::$app->request->get('time');
        $searchtime = yii::$app->request->get('searchtime');
        if($searchtime != 1){
            $time = '';
        }
        $Nodeform = new NodeForm();
        $return = $Nodeform->getPermissions($this->type, $name, $time);
        $return['name'] = $name;
        $return['time'] = $time;
        $return['searchtime'] = $searchtime;
        if($this->type == 1){
            return $this->render('index',$return);
        }else{
            return $this->render('permission',$return);
        }
    }

    /**
     * Displays a single AuthItem model.
     * @param  string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem(null);
        $model->type = $this->type;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($this->type == 1){
                return $this->success("角色添加成功", Url::toRoute('/rbac/role/index'));
            }else{
                return $this->redirect(['view', 'id' => $model->name]);
            }
        } else {
            if($this->type == 1){
                return $this->render('rolecreate', ['model' => $model]);
            }else{
                return $this->render('permissioncreate', ['model' => $model]);
            }
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param  string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        }
        if($this->type == 1){
            return $this->render('roleupdate', ['model' => $model]);
        }else{
            return $this->render('permissionupdate', ['model' => $model]);
        }

    }

    /**
     * 删除一个现有的authitem模型。
     * 如果删除是成功的
     * @param  string $id
     * @return Json status 100删除成功
     */
    public function actionDelete()
    {
        $id = yii::$app->request->post('id');
        if($this->type ==1){
            if($id == "超级管理员"){
                $this->ajaxReturn(json_encode(array('status' => 102)));
            }else{
                $model = $this->findModel($id);
                $count = Yii::$app->getAuthManager()->remove($model->item);
                Helper::invalidate();
                if($count > 0){
                    $this->ajaxReturn(json_encode(array('status' => 100)));
                }else{
                    $this->ajaxReturn(json_encode(array('status' => 101)));
                }
            }
        }else{
            if($id == '建站工具' || $id == '用户管理' || $id == '权限管理'){
                $this->ajaxReturn(json_encode(array('status' => 102)));
            }else{
                $model = $this->findModel($id);
                $count = Yii::$app->getAuthManager()->remove($model->item);
                Helper::invalidate();
                if($count > 0){
                    $this->ajaxReturn(json_encode(array('status' => 100)));
                }else{
                    $this->ajaxReturn(json_encode(array('status' => 101)));
                }
            }
        }
    }

    /**
     * 批量删除现有的authitem模型。
     * 如果删除是成功的
     * @param  string $id
     * @return mixed
     */
    public function actionDeleteselect()
    {
        $ids = yii::$app->request->post('ids');
        if($this->type == 1){
            foreach ($ids as $key => $item){
                if($item != '超级管理员'){
                    $model = $this->findModel($item);
                    Yii::$app->getAuthManager()->remove($model->item);
                    Helper::invalidate();
                }
            }
        }else{
            foreach ($ids as $key => $item){
                if($item != '建站工具' && $item != '用户管理' && $item != '权限管理'){
                    $model = $this->findModel($item);
                    Yii::$app->getAuthManager()->remove($model->item);
                    Helper::invalidate();
                }
            }
        }
        $this->ajaxReturn(json_encode(array('status' => 100)));
    }

    /**
     * 批量删除现有的authitem模型。
     * 如果删除是成功的
     * @param  string $id
     * @return mixed
     */
    public function actionDeleteall()
    {
        $flag = false;
        $NodeForm = new NodeForm();
        $ids = $NodeForm->getPermissionsids($this->type);
        if($this->type == 1){
            foreach ($ids as $key => $item){
                if(($item['name'] != '超级管理员')){
                    $model = $this->findModel($item['name']);
                    Yii::$app->getAuthManager()->remove($model->item);
                    Helper::invalidate();
                    $flag = true;
                }
            }
        }else{
            foreach ($ids as $key => $item){
                if($item['name'] != '建站工具' && $item['name'] != '用户管理' && $item['name'] != '权限管理'){
                    $model = $this->findModel($item);
                    Yii::$app->getAuthManager()->remove($model->item);
                    Helper::invalidate();
                    $flag = true;
                }
            }
        }
        if($flag){
            $this->ajaxReturn(json_encode(array('status' => 100)));
        }else{
            $this->ajaxReturn(json_encode(array('status' => 103)));
        }

    }

    /**
     * Assign items
     * @param string $id
     * @return array
     */
    public function actionAssign($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->addChildren($items);
        Yii::$app->getResponse()->format = 'json';

        return array_merge($model->getItems(), ['success' => $success]);
    }

    /**
     * Assign or remove items
     * @param string $id
     * @return array
     */
    public function actionRemove($id)
    {
        $items = Yii::$app->getRequest()->post('items', []);
        $model = $this->findModel($id);
        $success = $model->removeChildren($items);
        Yii::$app->getResponse()->format = 'json';

        return array_merge($model->getItems(), ['success' => $success]);
    }

    /**
     * @inheritdoc
     */
    public function getViewPath()
    {
        return $this->module->getViewPath() . DIRECTORY_SEPARATOR . 'item';
    }

    /**
     * Label use in view
     * @throws NotSupportedException
     */
    public function labels()
    {
        throw new NotSupportedException(get_class($this) . ' does not support labels().');
    }

    /**
     * Type of Auth Item.
     * @return integer
     */
    public function getType()
    {
        
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $auth = Yii::$app->getAuthManager();
        $item = $this->type === Item::TYPE_ROLE ? $auth->getRole($id) : $auth->getPermission($id);
        if ($item) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
