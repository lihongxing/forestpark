<?php

namespace app\modules\rbac\controllers;

use app\common\base\AdminbaseController;
use app\modules\rbac\components\MenuHelper;
use Yii;
use app\modules\rbac\models\Menu;
use app\modules\rbac\models\searchs\Menu as MenuSearch;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\rbac\components\Helper;

/**
 * MenuController implements the CRUD actions for Menu model.
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class MenuController extends AdminbaseController
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
                    'deletesel' => ['post']
                ],
            ],
        ];
    }

    /**
     * 列出所有菜单模型。
     * @return mixed
     */
    public function actionIndex()
    {

        $status = yii::$app->request->get('status');
        $page = yii::$app->request->get('page');
        $status = isset($status)? $status : -1;
        $levelmenulist = MenuHelper::getMenuList();
        $searchModel = new MenuSearch;
        $dataProvider = $searchModel->search(Yii::$app->request->getQueryParams());
        $Menumodel = new Menu();
        //显示或者隐藏搜索条件设置
        $Menumodelquery = $Menumodel->find();
        switch($status){
            case 1:
                $Menumodelquery = $Menumodelquery->where(['like', 'data', '"visible":"true"']);
                break;
            case 0:
                $Menumodelquery = $Menumodelquery->where(['like', 'data', '"visible":"false"']);
                break;
        }
        //菜单名称搜索
        $name = trim(yii::$app->request->get('name'));
        if(isset($name)){
            $Menumodelquery = $Menumodelquery->andWhere(['like', 'name', $name]);
        }
        $pages = new Pagination(['totalCount' => $Menumodelquery->count(), 'pageSize' => '10']);
        $key = $page.$status.$name;
        $menus = Yii::$app->cache->get($key);
        if($menus == false) {
            $dependency = new \yii\caching\DbDependency([
                'sql'=>'SELECT COUNT(*) FROM {{%menu}}',
            ]);
            $menus = $Menumodelquery
                ->offset($pages->offset)
                ->limit($pages->limit)
                ->asArray()
                ->all();
            Yii::$app->cache->set($key,$menus, '', $dependency);
        }
        foreach($menus as $key => &$item){
            if(!empty($item['parent'])){
                $parent = $Menumodel->find()
                    ->where(['id' => $item['parent']])
                    ->one();
                $item['parent'] = $parent['name'];
            }else{
                $item['parent'] = '顶级菜单';
            }
            $data = json_decode($item['data']);
            $item['status'] = $data->visible == 'true' ? 1:0;
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'levelmenulist' => $levelmenulist,
            'memus' => $menus,
            'pages' => $pages,
            'status' => $status
        ]);
    }

    /**
     * Displays a single Menu model.
     * @param  integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * 创建一个新的菜单模型。
     * 如果创建成功，浏览器将被重定向到“视图”页面。
     * @return 混合的
     */
    public function actionCreate()
    {
        $model = new Menu;
        $post = yii::$app->request->post();
        $parent_name = $post['Menu']['parent_name'];
        //获取父级菜单对应的id
        if (!empty($parent_name)) {
            $menuinfo = $model->find()
                ->where(array('name' => $parent_name))
                ->asArray()
                ->one();
            if (empty($menuinfo)) {
                $this->error('当前父级菜单不存在，请重新输入父级菜单');
            }
            $post['Menu']['parent'] = $menuinfo['id'];
        }
        $icon = $post['icon'];
        $show = $post['show'];
        $data = array();
        if (!empty($show)) {
            if ($show == 'show') {
                $data['visible'] = 'true';
            } else {
                $data['visible'] = 'false';
            }
        } else {
            $data['visible'] = 'true';
        }
        if (!empty($icon)) {
            $data['icon'] = $icon;
        } else {
            $data['icon'] = 'fa fa-circle-o';
        }
        $post['Menu']['data'] = json_encode($data);

        if ($model->load($post) && $model->save()) {
            Helper::invalidate();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 更新现有的菜单模型。
     * 如果更新成功，浏览器将被重定向到“视图”页面。
     * @param  integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->menuParent) {
            $model->parent_name = $model->menuParent->name;
        }
        $post = yii::$app->request->post();
        $parent_name = $post['Menu']['parent_name'];
        //获取父级菜单对应的id
        $menuinfo = $model->find()
            ->where(array('name' => $parent_name))
            ->asArray()
            ->one();
        $post['Menu']['parent'] = $menuinfo['id'];
        if (yii::$app->request->isPost) {
            $icon = $post['icon'];
            $show = $post['show'];
            $data = array();
            if ($show == 'show') {
                $data['visible'] = 'true';
            } else {
                $data['visible'] = 'false';
            }
            if (!empty($icon)) {
                $data['icon'] = $icon;
            }
            $post['Menu']['data'] = json_encode($data);
            if ($model->load($post) && $model->save()) {
                Helper::invalidate();
                return $this->redirect(['index']);
            } else {
                $errors = $model->getErrors();
                foreach ($errors as $key => $item) {
                    $error = $item[0];
                    break;
                }
                $this->error($error);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param  integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $id = yii::$app->request->post('id');
        if(!empty($id)){
            $menumodel = $this->findModel($id);
            if($menumodel->type == 1){
                echo json_encode(['status' => 103]);
            }else{
                $count = $menumodel->delete();
                if($count > 0){
                    Helper::invalidate();
                    echo json_encode(['status' => 100]);
                }else{
                    echo json_encode(['status' => 102]);
                }
            }
        }else{
            echo json_encode(['status' => 101]);
        }
    }

    /**
     * 查找基于它的主键值的菜单模型。
     * 如果模型没有被发现，404的HTTP会抛出异常。
     * @param  integer $id
     * @return Menu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 验证菜单名称是否存在
     * @param name 菜单名称
     * @return boolean true：不存在， false：已经存在
     */
    public function actionCheckmenuname()
    {
        $_POST = yii::$app->request->post();
        $name = $_POST['Menu']['name'];
        $menumodel = new Menu();
        $condition = array(
            'name' => $name
        );
        $return = $menumodel->find()
            ->where($condition)
            ->one();
        if (!empty($return)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * 验证父级菜单是否存在
     * @param parent_name 父级菜单名称
     * @return boolean true：不存在， false：已经存在
     */
    public function actionCheckparentname()
    {
        $_POST = yii::$app->request->post();
        $parent_name = $_POST['Menu']['parent_name'];
        $menumodel = new Menu();
        $condition = array(
            'name' => $parent_name
        );
        $return = $menumodel->find()
            ->where($condition)
            ->one();
        if (empty($return)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * 菜单批量删除
     */
    public function actionDeletesel()
    {
        $ids = yii::$app->request->post('ids');
        $num = count($ids);
        if(!empty($ids)){
            $numtmp = 0;
            foreach($ids as $key => $id){
                $menumodel = $this->findModel($id);
                if($menumodel->type != 1){
                    $count = $menumodel->delete();
                    if($count > 0){
                        Helper::invalidate();
                        $numtmp++;
                    }
                }
            }
            if($numtmp > 0 && $numtmp == $num){
                $message['status'] = 100;
            }else if($numtmp > 0 && $numtmp < $num){
                $message['status'] = 100;
            }else{
                $message['status'] = 103;
            }
        }else{
            $message['status'] = 102;
        }
        $this->ajaxReturn(json_encode($message));
    }

    /**
     * 删除系统菜单之外的所有菜单
     * @throws \Exception
     */
    public function actionDeleteall()
    {
        $Menumodel = new Menu();
        $count = $Menumodel->deleteAll('type != 1');
        if($count > 0 ){
            $message['status'] = 100;
        }else{
            $message['status'] = 101;
        }
        $this->ajaxReturn(json_encode($message));
    }

    /**
     * 菜单显示或者隐藏方法
     *
     */
    public function actionStatus()
    {
        $id = trim(yii::$app->request->post('id'));
        $status = yii::$app->request->post('status');
        if(isset($id)){
            $Menumodel = new Menu();
            $menuinfo = $Menumodel->find()->select(['data','type'])->where(['id' => $id])->asArray()->one();
            if($menuinfo['type'] == 1){
                $this->ajaxReturn(json_encode(['status' => 104]));
            }else{
                $dataarr = json_decode($menuinfo['data']);
                $dataarr->visible = ($status == 'true') ? "true" : "false";
                $attributes = ['data' => json_encode($dataarr)];
                $condition = "id=:id";
                $params = [':id' => $id];
                $Menumodel->updateAll($attributes, $condition, $params);
            }
        }
    }
}
