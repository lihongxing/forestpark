<?php

namespace app\modules\admin\controllers;

use app\common\base\AdminbaseController;
use app\models\Notice;
use yii\data\Pagination;
use yii;

class NoticeController extends AdminbaseController
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
     * 前台公告管理列表
     * @return 跳转到视图文件
     */
    public function actionNoticeList()
    {
        $Notice = new Notice();
        $Noticequery = $Notice->find();
        $GET = yii::$app->request->get();
        if(!empty($GET['keyword'])){
            $Noticequery = $Noticequery->where(['or',
                ['like', 'not_name', $GET['keyword']],
                ['like', 'not_title', $GET['keyword']],
            ]);
        };
        $pages = new Pagination(['totalCount' => $Noticequery->count(), 'pageSize' => 10]);
        $notices = $Noticequery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return $this->render('Noticelist', array(
            'notices' => $notices,
            'pages' => $pages,
            'GET' => $GET

        ));
    }

    /**
     * 前台公告添加，修改
     * @return 跳转到视图文件
     */
    public function actionNoticeForm()
    {
        $Notice = new Notice();
        if(yii::$app->request->isPost){
            $post = yii::$app->request->post();
            //判断是修改还是添加
            if(!empty($post['not_id'])){
                $attributes = $post['Notice'];
                $condition = 'not_id=:not_id';
                $params = [
                    ':not_id' => $post['not_id']
                ];
                $count = $Notice->updateAll($attributes, $condition, $params);
                if($count > 0){
                    return $this->success('公告修改成功',yii\helpers\Url::toRoute('/admin/notice/notice-list'));
                }else{
                    return $this->render('Noticeform', [
                        'Notice' => $Notice,
                    ]);
                }
            }else{
                $Notice->setAttribute('not_title', $post['Notice']['not_title']);
                $Notice->setAttribute('not_url', $post['Notice']['not_url']);
                $Notice->setAttribute('not_show', $post['Notice']['not_show']);
                $Notice->setAttribute('not_name', $post['Notice']['not_name']);
                $Notice->setAttribute('not_order', $post['Notice']['not_order']);
                $Notice->setAttribute('not_content', $post['Notice']['not_content']);
                $Notice->setAttribute('not_addtime', time());
                $Notice->setAttribute('not_updatetime', strtotime($post['Notice']['not_updatetime']));
                $Notice->save();
                if ($Notice->save()) {
                    return $this->success('公告添加成功',yii\helpers\Url::toRoute('/admin/notice/notice-list'));
                } else {
                    return $this->render('Noticeform', [
                        'Notice' => $Notice,
                    ]);
                }
            }
        }else{
            $not_id = yii::$app->request->get('not_id');
            if(!empty($not_id)){
                $noticeinfo = $Notice->find()
                    ->where(array('not_id' => $not_id))
                    ->asArray()
                    ->one();
            }
            return $this->render('Noticeform', [
                    'Notice' => $noticeinfo,
            ]);
        }
    }

    /**
     * 公告删除方法
     * @type POST
     * @param Int not_id：需要删除的公告菜单id Int type：删除的类型1代表单个删除，2代表批量删除，3代表全部删除
     * @return Int status：删除的状态 100代表删除成功，101|103|102代表删除失败
     */
    public function actionNoticeDelete()
    {
        $type = yii::$app->request->post('type');
        $Notice = new Notice();
        switch($type){
            case 1:
                $not_id = yii::$app->request->post('not_id');
                if(!empty($not_id)){
                    $menumodel = $Notice->find()
                        ->where(['not_id' => $not_id])
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
                $not_ids = yii::$app->request->post('not_ids');
                if(!empty($not_ids)){
                    $count = $Notice->deleteAll(['in', 'not_id', $not_ids]);
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
                $count = $Notice->deleteAll();
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
     * 公告的显示与隐藏
     * @param Int not_id:需要显示或者隐藏的的公告not_id
     * @param boolean status true：显示，false：隐藏
     */
    public function actionNoticeStatus()
    {
        $not_id = trim(yii::$app->request->post('not_id'));
        $status = yii::$app->request->post('status');
        if(!empty($not_id)){
            $Notice = new Notice();
            $attributes = ['not_show' => $status == 'true' ? 1 : 0] ;
            $condition = "not_id=:not_id";
            $params = [':not_id' => $not_id];
            $Notice->updateAll($attributes, $condition, $params);
            echo $status;
        }
    }
}
