<?php

namespace app\modules\admin\controllers;

use app\common\base\AdminbaseController;
use app\models\NavigationForm;
use yii\data\Pagination;
use yii;

class SiteController extends AdminbaseController
{
    public $layout='main';//设置默认的布局文件
    
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    /**
     * 后台站点管理站点设置基本信息设置
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    /**
     * 后台站点管理站点设置附件设置
     * @return 跳转到视图文件
     */
    public function actionUpfile()
    {
        return $this->render('upfile');
    }

    /**
     * 欢迎页面
     * @return 跳转到视图文件
     */
    public function actionWelcome()
    {
        return $this->render('welcome');
    }

    /**
     * 没有权限访问的提示页面
     * @return 跳转到视图文件
     */
    public function actionForbidden()
    {
        return $this->render('forbidden');
    }

    /**
     * 图标加载方法
     * @return 跳转到视图文件
     */
    public function actionIcon()
    {
        $callback = yii::$app->request->get('callback');
        return $this->renderPartial('icon',[
            'callback'=>$callback
        ]);
    }

    /**
     * 站点信息设置方法
     * @return 跳转到视图文件
     */
    public function actionSettings()
    {
        if(yii::$app->request->isPost){
            $siteinfo = yii::$app->request->post('settings');
            $file = "../config/siteinfo.php";
            $str = "<?php return " . var_export($siteinfo, TRUE) . ";?>";
            $count = file_put_contents($file, $str);
            if($count > 0){
                $this->success("站点信息修改成功！", "");
            }else{
                $this->error("站点信息修改失败", "");
            }
        }else{
            return $this->render('settings',[
                'settings' => yii::$app->params['siteinfo']
            ]);
        }
    }

    /**
     * 前台导航管理列表
     * @return 跳转到视图文件
     */
    public function actionNavigtionList()
    {
        $NavigationForm = new NavigationForm();
        $Navigationquery = $NavigationForm->find();
        $GET = yii::$app->request->get();
        if(!empty($GET['keyword'])){
            $Navigationquery = $Navigationquery->where(['or',
                ['like', 'nav_name', $GET['keyword']],
                ['like', 'nav_title', $GET['keyword']],
            ]);
        };
        $pages = new Pagination(['totalCount' => $Navigationquery->count(), 'pageSize' => 10]);
        $navigations = $Navigationquery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return $this->render('navigtionlist', array(
            'navigations' => $navigations,
            'pages' => $pages,
            'GET' => $GET

        ));
    }

    /**
     * 前台导航添加，修改
     * @return 跳转到视图文件
     */
    public function actionNavigtionForm()
    {
        $NavigationForm = new NavigationForm();
        if(yii::$app->request->isPost){
            $post = yii::$app->request->post();
            //判断是修改还是添加
            if(!empty($post['nav_id'])){
                $attributes = $post['NavigationForm'];
                $condition = 'nav_id=:nav_id';
                $params = [
                    ':nav_id' => $post['nav_id']
                ];
                $count = $NavigationForm->updateAll($attributes, $condition, $params);
                if($count > 0){
                    return $this->success('导航修改成功',yii\helpers\Url::toRoute('/admin/site/navigtion-list'));
                }else{
                    return $this->render('navigtionform', [
                        'NavigationForm' => $NavigationForm,
                    ]);
                }
            }else{
                $NavigationForm->setAttribute('nav_hot', 0);
                $nav_parent = $post['NavigationForm']['nav_parent'];
                $NavigationForm->setAttribute('nav_lv', 1);
                //获取父级菜单对应的id
                if (!empty($nav_parent)) {
                    $navinfo = $NavigationForm->find()
                        ->where(array('nav_parent' => $nav_parent))
                        ->asArray()
                        ->one();
                    if (empty($navinfo)) {
                        $this->error('当前父级菜单不存在，请重新输入父级菜单');
                    }
                    $NavigationForm->setAttribute('nav_parent', $navinfo['id']);
                    $NavigationForm->setAttribute('nav_lv', 2);
                }
                $NavigationForm->setAttribute('nav_title', $post['NavigationForm']['nav_title']);
                $NavigationForm->setAttribute('nav_href', $post['NavigationForm']['nav_href']);
                $NavigationForm->setAttribute('nav_show', $post['NavigationForm']['nav_show']);
                $NavigationForm->setAttribute('nav_name', $post['NavigationForm']['nav_name']);
                $NavigationForm->setAttribute('nav_order', $post['NavigationForm']['nav_order']);
                if ($NavigationForm->save()) {
                    return $this->success('导航添加成功',yii\helpers\Url::toRoute('/admin/site/navigtion-list'));
                } else {
                    return $this->render('navigtionform', [
                        'NavigationForm' => $NavigationForm,
                    ]);
                }
            }
        }else{
            $nav_id = yii::$app->request->get('nav_id');
            $navinfo = $NavigationForm->find()
                ->where(array('nav_id' => $nav_id))
                ->asArray()
                ->one();
            return $this->render('navigtionform', [
                    'NavigationForm' => $navinfo,
            ]);
        }
    }

    /**
     * 验证父级导航是否存在
     * @type POST
     * @param String NavigationForm[nav_parent] 父级菜单的名称
     * @return boolean false：不存在， true：已经存在
     */
    public function actionCheckNav_parent()
    {
        $_POST = yii::$app->request->post();
        $nav_parent = $_POST['NavigationForm']['nav_parent'];
        $NavigationForm = new NavigationForm();
        $condition = array(
            'nav_name' => $nav_parent
        );
        $return = $NavigationForm->find()
            ->where($condition)
            ->one();
        if (empty($return)) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * 导航删除方法
     * @type POST
     * @param Int nav_id：需要删除的导航菜单id Int type：删除的类型1代表单个删除，2代表批量删除，3代表全部删除
     * @return Int status：删除的状态 100代表删除成功，101|103|102代表删除失败
     */
    public function actionNavDelete()
    {
        $type = yii::$app->request->post('type');
        $NavigationForm = new NavigationForm();
        switch($type){
            case 1:
                $nav_id = yii::$app->request->post('nav_id');
                if(!empty($nav_id)){
                    $menumodel = $NavigationForm->find()
                        ->where(['nav_id' => $nav_id])
                        ->one();
                    $count = $menumodel->delete();
                    if($count > 0){
                        $message['status'] = 100;
                    }else {
                        $message['status'] = 102;
                    }
                }else{
                    $message['status'] = 101;
                }
                break;
            case 2:
                $nav_ids = yii::$app->request->post('nav_ids');
                if(!empty($nav_ids)){
                    $count = $NavigationForm->deleteAll(['in', 'nav_id', $nav_ids]);
                    if($count > 0){
                        $message['status'] = 100;
                    }else {
                        $message['status'] = 101;
                    }
                }else{
                    $message['status'] = 102;
                }
                break;
            case 3:
                $count = $NavigationForm->deleteAll();
                if($count > 0 ){
                    $message['status'] = 100;
                }else{
                    $message['status'] = 101;
                }
                break;
        }
        return $this->ajaxReturn(json_encode($message));

    }

    /**
     * 导航的显示与隐藏
     * @param Int nav_id:需要显示或者隐藏的的导航nav_id
     * @param boolean status true：显示，false：隐藏
     */
    public function actionNavStatus()
    {
        $nav_id = trim(yii::$app->request->post('nav_id'));
        $status = yii::$app->request->post('status');
        if(!empty($nav_id)){
            $NavigationForm = new NavigationForm();
            $attributes = ['nav_show' => $status == 'true' ? 1 : 0] ;
            $condition = "nav_id=:nav_id";
            $params = [':nav_id' => $nav_id];
            $NavigationForm->updateAll($attributes, $condition, $params);
            echo $status;
        }
    }
}
