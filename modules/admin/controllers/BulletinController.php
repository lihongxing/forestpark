<?php

namespace app\modules\admin\controllers;

use app\common\base\AdminbaseController;
use app\models\Bulletin;
use app\modules\rbac\models\User;
use yii\data\Pagination;
use yii;

class BulletinController extends AdminbaseController
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
     * 前台通知通报管理列表
     * @return 跳转到视图文件
     */
    public function actionBulletinList()
    {
        $Bulletin = new Bulletin();
        $Bulletinquery = $Bulletin->find();
        $GET = yii::$app->request->get();
        if(!empty($GET['keyword'])){
            $Bulletinquery = $Bulletinquery->where(['or',
                ['like', 'bul_undertakingunit', $GET['keyword']],
                ['like', 'bul_title', $GET['keyword']],
            ]);
        };
        $pages = new Pagination(['totalCount' => $Bulletinquery->count(), 'pageSize' => 10]);
        $bulletins = $Bulletinquery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->asArray()
            ->all();
        return $this->render('bulletinlist', array(
            'bulletins' => $bulletins,
            'pages' => $pages,
            'GET' => $GET

        ));
    }

    /**
     * 前台通知通报添加，修改
     * @return 跳转到视图文件
     */
    public function actionBulletinForm()
    {
        $Bulletin = new Bulletin();
        if(yii::$app->request->isPost){
            $post = yii::$app->request->post();
            $post['Bulletin']['bul_addtime'] = strtotime($post['Bulletin']['bul_addtime']);
            //判断是修改还是添加
            if(!empty($post['bul_id'])){
                $attributes = $post['Bulletin'];
                $attributes['bul_examinetime'] = '';
                $attributes['bul_isexamine'] = 1;
                $condition = 'bul_id=:bul_id';
                $params = [
                    ':bul_id' => $post['bul_id']
                ];
                $count = $Bulletin->updateAll($attributes, $condition, $params);
                if($count > 0){
                    return $this->success('通知通报修改成功',yii\helpers\Url::toRoute('/admin/bulletin/bulletin-list'));
                }else{
                    return $this->render('bulletinform', [
                        'Bulletin' => $Bulletin,
                    ]);
                }
            }else{
                //获取当前年份的起始结束时间戳
                $t = time();
                $end = mktime(23,59,59,12,31,date("Y",$t));
                $begin = mktime(0,0,0,1,1,date("Y",$t));
                $Bulletin = new Bulletin();
                //统计当年共发布多少通知通报
                $count = $Bulletin->find()->where(['between', 'bul_addtime', $begin, $end] )->count();
                $Bulletin->setAttribute('bul_title', $post['Bulletin']['bul_title']);
                $Bulletin->setAttribute('bul_order', $post['Bulletin']['bul_order']);
                $Bulletin->setAttribute('bul_undertakingunit', $post['Bulletin']['bul_undertakingunit']);
                $Bulletin->setAttribute('bul_content', $post['Bulletin']['bul_content']);
                $Bulletin->setAttribute('bul_releaseuser', $post['Bulletin']['bul_releaseuser']);
                $Bulletin->setAttribute('bul_issuer', $post['Bulletin']['bul_issuer']);
                $Bulletin->setAttribute('bul_number', date("Y").'〔'.sprintf("%04d", $count+1).'〕');
                $Bulletin->setAttribute('bul_addtime', $post['Bulletin']['bul_addtime']);
                if ($Bulletin->save()) {
                    return $this->success('通知通报添加成功',yii\helpers\Url::toRoute('/admin/bulletin/bulletin-list'));
                } else {
                    return $this->render('bulletinform', [
                        'Bulletin' => $Bulletin,
                    ]);
                }
            }
        }else{
            $bul_id = yii::$app->request->get('bul_id');
            $bulletininfo = $Bulletin->find()
                ->where(array('bul_id' => $bul_id))
                ->asArray()
                ->one();
            if(!empty($bulletininfo)){
                //获取当前通知通告的发布人签发人信息
                $usermodel = new User();
                $issuer = $usermodel->find()
                    ->select(['id','username','head_img'])
                    ->where(array('id' => $bulletininfo['bul_issuer']))
                    ->asArray()
                    ->one();
                $releaseuser = $usermodel->find()
                    ->select(['id','username','head_img'])
                    ->where(array('id' => $bulletininfo['bul_releaseuser']))
                    ->asArray()
                    ->one();
            }

            return $this->render('bulletinform', [
                'Bulletin' => $bulletininfo,
                'issuer' => $issuer,
                'releaseuser' => $releaseuser

            ]);
        }
    }

    /**
     * 通知通报删除方法
     * @type POST
     * @param Int bul_id：需要删除的通知通报菜单id Int type：删除的类型1代表单个删除，2代表批量删除，3代表全部删除
     * @return Int status：删除的状态 100代表删除成功，101|103|102代表删除失败
     */
    public function actionBulletinDelete()
    {
        $type = yii::$app->request->post('type');
        $Bulletin = new Bulletin();
        switch($type){
            case 1:
                $bul_id = yii::$app->request->post('bul_id');
                if(!empty($bul_id)){
                    $menumodel = $Bulletin->find()
                        ->where(['bul_id' => $bul_id])
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
                $bul_ids = yii::$app->request->post('bul_ids');
                if(!empty($bul_ids)){
                    $count = $Bulletin->deleteAll(['in', 'bul_id', $bul_ids]);
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
                $count = $Bulletin->deleteAll();
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
     * 通知通报的显示与隐藏
     * @param Int bul_id:需要显示或者隐藏的的通知通报bul_id
     * @param boolean status true：显示，false：隐藏
     */
    public function actionBulletinStatus()
    {
        $bul_id = trim(yii::$app->request->post('bul_id'));
        $status = yii::$app->request->post('status');
        //判断当前审核人是否微通知通报签发人
        $Bulletin = new Bulletin();
        $bulletininfo = $Bulletin->find()
            ->select(['bul_issuer'])
            ->where(['bul_id' => $bul_id])
            ->asArray()
            ->one();
        if($bulletininfo['bul_issuer'] == yii::$app->user->id){
            if(!empty($bul_id)){
                $attributes = ['bul_isexamine' => $status == 'true' ? 2 : 3, 'bul_examinetime' => time()] ;
                $condition = "bul_id=:bul_id";
                $params = [':bul_id' => $bul_id];
                $count = $Bulletin->updateAll($attributes, $condition, $params);
                if($count > 0){
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
