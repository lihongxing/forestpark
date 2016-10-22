<?php

namespace app\modules\admin\controllers;
/**
 * 视屏播放的的增改查
 * User: lihongxing
 * Date: 2016/10/17
 * Time: 16:13
 */

use app\common\base\AdminbaseController;
use app\models\Message;
use app\models\Videoplay;
use app\modules\rbac\models\User;
use yii;

class VideoplayController extends AdminbaseController
{
    public $layout='main';//设置默认的布局文件

    /**
     * 视屏播放列表页
     * @param keyword：关键词 视屏播放的标题或者视屏播放的描述中包含的词
     * @return View 视屏播放列表页视图
     */
    public function actionVideoplayList()
    {
        $Videoplaymodel = new Videoplay();
        $Videoplayuery = $Videoplaymodel->find();
        $GET = yii::$app->request->get();
        if(!empty($GET['keyword'])){
            $Videoplayuery = $Videoplayuery->where(['or',
                ['like', 'vid_describe', $GET['keyword']],
                ['like', 'vid_title', $GET['keyword']],
            ]);
        };
        if(!empty($GET['id'])){
            $Videoplayuery = $Videoplayuery->andWhere(['vid_id'  => $GET['id']]);
        }
        $pages = new yii\data\Pagination(['totalCount' => $Videoplayuery->count(), 'pageSize' => 10]);
        $videoplays = $Videoplayuery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return $this->render('videoplaylist', array(
            'videoplays' => $videoplays,
            'pages' => $pages,
            'GET' => $GET

        ));
    }


    /**
     * 前台通知通报添加，修改
     * @return 跳转到视图文件
     */
    public function actionVideoplayForm()
    {
        if(yii::$app->request->isPost){
            $data = yii::$app->request->post();
            $Videoplaymodel = new Videoplay();
            if(empty($data['vid_id'])){
                $Usermodel = new User();
                $data['Videoplay']['vid_addtime'] = strtotime($data['Videoplay']['vid_addtime']);
                $userinfo = $Usermodel->SuperAdministrator(['id']);
                $data['Videoplay']['vid_issuer'] = json_encode([$userinfo['id']]);
                $Videoplaymodel->setAttributes($data['Videoplay'],false);
                if($Videoplaymodel->save()){
                    //添加审核人通知
                    $mes_data = [
                        'mes_title' => $data["Videoplay"]["vid_title"],
                        'mes_release_user' => $data['Videoplay']['vid_release_uid'],
                        'mes_issuer' => [$userinfo['id']],
                        'mes_sourse_id' => 'videoplay_'.$Videoplaymodel->primaryKey,
                        'mes_flag' => 1,
                        'mes_template' => 'videoplay_issuer',
                        'mes_module' => 'videoplay',
                        'mes_class' => 1,
                    ];
                    Message::create($mes_data, 1);
                    //添加发布人通知
                    $mes_data = [
                        'mes_title' => $data["Videoplay"]["vid_title"],
                        'mes_release_user' => $data['Videoplay']['vid_release_uid'],
                        'mes_issuer' => [$userinfo['id']],
                        'mes_sourse_id' => 'videoplay_'.$Videoplaymodel->primaryKey,
                        'mes_flag' => 2,
                        'mes_template' => 'videoplay_release',
                        'mes_module' => 'videoplay',
                        'mes_class' => 2,
                    ];
                    Message::create($mes_data, 1);
                    $this->success('添加成功',yii\helpers\Url::toRoute('/admin/videoplay/videoplay-list'));
                }else{
                    $this->error('添加失败');
                }
            }else{
                $Videoplaymodel = $Videoplaymodel->find()
                    ->where(['vid_id' => $data['vid_id']])
                    ->one();
                $data['Videoplay']['vid_examine_status'] = 1;
                $data['Videoplay']['vid_isexamine'] = 1;
                $data['Videoplay']['vid_addtime'] = strtotime($data['Videoplay']['vid_addtime']);
                $Videoplaymodel->setAttributes($data['Videoplay'],false);
                if($Videoplaymodel->save()){
                    Message::Messagedelete([$data['vid_id']], 'videoplay');
                    //添加审核人通知
                    $mes_data = [
                        'mes_title' => $data["Videoplay"]["vid_title"],
                        'mes_release_user' => $data['Videoplay']['vid_release_uid'],
                        'mes_issuer' => json_decode($Videoplaymodel['vid_issuer']),
                        'mes_sourse_id' => 'videoplay_'.$data['vid_id'],
                        'mes_template' => 'videoplay_issuer',
                        'mes_module' => 'videoplay',
                        'mes_flag' => 1,
                        'mes_class' => 1,
                    ];
                    Message::create($mes_data, 1);
                    //添加发布人通知
                    $mes_data = [
                        'mes_title' => $data["Videoplay"]["vid_title"],
                        'mes_release_user' => $data['Videoplay']['vid_release_uid'],
                        'mes_issuer' => json_decode($Videoplaymodel['vid_issuer']),
                        'mes_sourse_id' => 'videoplay_'.$data['vid_id'],
                        'mes_template' => 'videoplay_release',
                        'mes_module' => 'videoplay',
                        'mes_flag' => 2,
                        'mes_class' => 2,
                    ];
                    Message::create($mes_data, 1);
                    $this->success('修改成功',yii\helpers\Url::toRoute('/admin/videoplay/videoplay-list'));
                }else{
                    $this->error('修改失败');
                }
            }
        }else{
            $vid_id = yii::$app->request->get('vid_id');
            if(!empty($vid_id)){
                $Videoplaymodel = new Videoplay();
                $Videoplay = $Videoplaymodel->find()
                    ->where(array('vid_id' => $vid_id))
                    ->asArray()
                    ->one();
            }
            if(!empty($Videoplay)){
                $Usermodel = new User();
                //获取当前通知通告的发布人信息
                $releaseuser = $Usermodel->find()
                    ->select(['id','username','head_img'])
                    ->where(array('id' => $Videoplay['vid_release_uid']))
                    ->asArray()
                    ->one();
            }
            return $this->render('videoplayform', [
                'Videoplay' => $Videoplay,
                'releaseuser' => $releaseuser,
            ]);
        }

    }

    /**
     * 通知通报删除方法
     * @type POST
     * @param Int vid_id：需要删除的通知通报菜单id Int type：删除的类型1代表单个删除，2代表批量删除，3代表全部删除
     * @return Int status：删除的状态 100代表删除成功，101|103|102代表删除失败
     */
    public function actionVideoplayDelete()
    {
        $type = yii::$app->request->post('type');
        $Videoplaymodel = new Videoplay();
        switch($type){
            case 1:
                $vid_id = yii::$app->request->post('vid_id');
                if(!empty($vid_id)){
                    $Videoplaymodel = $Videoplaymodel->find()
                        ->where(['vid_id' => $vid_id])
                        ->one();
                    $count = $Videoplaymodel->delete();
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
                $vid_ids = yii::$app->request->post('vid_ids');
                if(!empty($vid_ids)){
                    $count = $Videoplaymodel->deleteAll(['in', 'vid_id', $vid_ids]);
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
                $vid_ids_tmp = $Videoplaymodel->find()->select('vid_id')->asArray()->all();
                $vid_ids = array();
                foreach($vid_ids_tmp as $item){
                    array_push($vid_ids, $item['vid_id']);
                }
                $count = $Videoplaymodel->deleteAll();
                if($count > 0 ){
                    $message['status'] = 100;
                }else{
                    $message['status'] = 101;
                }
                break;
        }
        //Message::Messagedelete($vid_ids, 'Videoplay');
        return $this->ajaxReturn(json_encode($message));
    }

    /**
     * 视屏播放的的审核
     * @param Int vid_id:需要显示或者隐藏的的通知通报vid_id
     * @param boolean status true：通过，false：未通过
     */
    public function actionVideoplayStatus()
    {
        $vid_id = trim(yii::$app->request->post('vid_id'));
        $status = yii::$app->request->post('status');
        //判断当前审核人是否微通知通报签发人
        $Videoplaymodel = new Videoplay();
        $Videoplayinfo = $Videoplaymodel->find()
            ->where(['vid_id' => $vid_id])
            ->asArray()
            ->one();
        $Videoplayinfo['vid_issuer'] = json_decode($Videoplayinfo['vid_issuer']);
        if(in_array(yii::$app->user->id, $Videoplayinfo['vid_issuer'])){
            if(!empty($vid_id)){
                $attributes = [
                    'vid_isexamine' => 2,
                    'vid_examine_time' => time(),
                    'vid_examine_status' => $status == 'true' ? 2 : 3,
                    'vid_examine_uid' => yii::$app->user->id
                ];
                $condition = "vid_id=:vid_id";
                $params = [':vid_id' => $vid_id];
                $count = $Videoplaymodel->updateAll($attributes, $condition, $params);
                if($count > 0){
                    //修改审核消息的状态
                    Message::updatestatus('videoplay_'.$vid_id, 'true', $Videoplayinfo['vid_release_uid']);
                    //添加审核通知（发布人）

                    $mes_data = [
                        'mes_title' => $Videoplayinfo["vid_title"],
                        'mes_release_user' => $Videoplayinfo['vid_release_uid'],
                        'mes_issuer' => $Videoplayinfo['vid_issuer'],
                        'mes_sourse_id' => 'videoplay_'.$Videoplayinfo['vid_id'],
                        'mes_flag' => 3,
                        'mes_template' => 'videoplay_examinerelease',
                        'mes_module' => 'videoplay',
                        'mes_class' => 2,
                    ];
                    Message::create($mes_data, 1);
                    //添加审核通知（审核人）
                    $mes_data = [
                        'mes_title' =>$Videoplayinfo["vid_title"],
                        'mes_release_user' => $Videoplayinfo['vid_release_uid'],
                        'mes_issuer' => $Videoplayinfo['vid_issuer'],
                        'mes_sourse_id' => 'videoplay_'.$Videoplayinfo['vid_id'],
                        'mes_flag' => 4,
                        'mes_template' => 'videoplay_examineissuer',
                        'mes_module' => 'videoplay',
                        'mes_class' => 2,
                    ];
                    Message::create($mes_data, 1);
                    $message['status'] = 100;
                }else{
                    $message['status'] = 101;
                }
            }
        }else{
            $message['status'] = 102;
        }
        return $this->ajaxReturn(json_encode($message));
    }
}
