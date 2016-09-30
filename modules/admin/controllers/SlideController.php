<?php

namespace app\modules\admin\controllers;

use app\common\base\AdminbaseController;
use app\models\Slide;
use yii\data\Pagination;
use yii;

class SlideController extends AdminbaseController
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
     * 前台幻灯片管理列表
     * @return 跳转到视图文件
     */
    public function actionSlideList()
    {
        $Slide = new Slide();
        $Slidequery = $Slide->find();
        $GET = yii::$app->request->get();
        if(!empty($GET['keyword'])){
            $Slidequery = $Slidequery->where(['or',
                ['like', 'sli_name', $GET['keyword']],
                ['like', 'sli_title', $GET['keyword']],
            ]);
        };
        $pages = new Pagination(['totalCount' => $Slidequery->count(), 'pageSize' => 10]);
        $slides = $Slidequery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return $this->render('slidelist', array(
            'slides' => $slides,
            'pages' => $pages,
            'GET' => $GET

        ));
    }

    /**
     * 前台幻灯片添加，修改
     * @return 跳转到视图文件
     */
    public function actionSlideForm()
    {
        $Slide = new Slide();
        if(yii::$app->request->isPost){
            $post = yii::$app->request->post();
            //判断是修改还是添加
            if(!empty($post['sli_id'])){
                $attributes = $post['Slide'];
                $condition = 'sli_id=:sli_id';
                $params = [
                    ':sli_id' => $post['sli_id']
                ];
                $count = $Slide->updateAll($attributes, $condition, $params);
                if($count > 0){
                    return $this->success('幻灯片修改成功',yii\helpers\Url::toRoute('/admin/slide/slide-list'));
                }else{
                    return $this->render('slideform', [
                        'Slide' => $Slide,
                    ]);
                }
            }else{
                $Slide->setAttribute('sli_title', $post['Slide']['sli_title']);
                $Slide->setAttribute('sli_url', $post['Slide']['sli_url']);
                $Slide->setAttribute('sli_show', $post['Slide']['sli_show']);
                $Slide->setAttribute('sli_name', $post['Slide']['sli_name']);
                $Slide->setAttribute('sli_order', $post['Slide']['sli_order']);
                $Slide->setAttribute('sli_pic', $post['Slide']['sli_pic']);
                $Slide->setAttribute('sli_des', $post['Slide']['sli_des']);
                if ($Slide->save()) {
                    return $this->success('幻灯片添加成功',yii\helpers\Url::toRoute('/admin/slide/slide-list'));
                } else {
                    return $this->render('slideform', [
                        'Slide' => $Slide,
                    ]);
                }
            }
        }else{
            $sli_id = yii::$app->request->get('sli_id');
            $sliinfo = $Slide->find()
                ->where(array('sli_id' => $sli_id))
                ->asArray()
                ->one();
            return $this->render('slideform', [
                    'Slide' => $sliinfo,
            ]);
        }
    }

    /**
     * 幻灯片删除方法
     * @type POST
     * @param Int sli_id：需要删除的幻灯片菜单id Int type：删除的类型1代表单个删除，2代表批量删除，3代表全部删除
     * @return Int status：删除的状态 100代表删除成功，101|103|102代表删除失败
     */
    public function actionSlideDelete()
    {
        $type = yii::$app->request->post('type');
        $Slide = new Slide();
        switch($type){
            case 1:
                $sli_id = yii::$app->request->post('sli_id');
                if(!empty($sli_id)){
                    $menumodel = $Slide->find()
                        ->where(['sli_id' => $sli_id])
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
                $sli_ids = yii::$app->request->post('sli_ids');
                if(!empty($sli_ids)){
                    $count = $Slide->deleteAll(['in', 'sli_id', $sli_ids]);
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
                $count = $Slide->deleteAll();
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
     * 幻灯片的显示与隐藏
     * @param Int sli_id:需要显示或者隐藏的的幻灯片sli_id
     * @param boolean status true：显示，false：隐藏
     */
    public function actionSlideStatus()
    {
        $sli_id = trim(yii::$app->request->post('sli_id'));
        $status = yii::$app->request->post('status');
        if(!empty($sli_id)){
            $Slide = new Slide();
            $attributes = ['sli_show' => $status == 'true' ? 1 : 0] ;
            $condition = "sli_id=:sli_id";
            $params = [':sli_id' => $sli_id];
            $Slide->updateAll($attributes, $condition, $params);
            echo $status;
        }
    }
}
