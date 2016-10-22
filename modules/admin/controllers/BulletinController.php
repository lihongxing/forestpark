<?php

namespace app\modules\admin\controllers;

use app\common\base\AdminbaseController;
use app\common\core\GlobalHelper;
use app\models\Bulletin;
use app\models\Message;
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
                'mes_class' => 'yii\web\ErrorAction',
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
        if(!empty($GET['id'])){
            $Bulletinquery = $Bulletinquery->andWhere(['bul_id'  => $GET['id']]);
        }
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
                    Message::Messagedelete([$post['bul_id']], 'bulletin');
                    //添加审核人通知
                    $mes_data = [
                        'mes_title' => $post["Bulletin"]["bul_title"],
                        'mes_release_user' => $post['Bulletin']['bul_releaseuser'],
                        'mes_issuer' => $post['Bulletin']['bul_issuer'],
                        'mes_sourse_id' => 'bulletin_'.$post['bul_id'],
                        'mes_template' => 'bulletin_issuer',
                        'mes_module' => 'bulletin',
                        'mes_flag' => 1,
                        'mes_class' => 1,
                    ];
                    Message::create($mes_data, 1);
                    //添加发布人通知
                    $mes_data = [
                        'mes_title' => $post["Bulletin"]["bul_title"],
                        'mes_release_user' => $post['Bulletin']['bul_releaseuser'],
                        'mes_issuer' => $post['Bulletin']['bul_issuer'],
                        'mes_sourse_id' => 'bulletin_'.$post['bul_id'],
                        'mes_template' => 'bulletin_release',
                        'mes_module' => 'bulletin',
                        'mes_flag' => 2,
                        'mes_class' => 2,
                    ];
                    Message::create($mes_data, 1);
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
                $Bulletin->setAttribute('bul_issuer', json_encode($post['Bulletin']['bul_issuer']));
                $Bulletin->setAttribute('bul_number', date("Y").'〔'.sprintf("%04d", $count+1).'〕');
                $Bulletin->setAttribute('bul_addtime', $post['Bulletin']['bul_addtime']);
                if ($Bulletin->save()) {
                    //添加审核人通知
                    $mes_data = [
                        'mes_title' => $post["Bulletin"]["bul_title"],
                        'mes_release_user' => $post['Bulletin']['bul_releaseuser'],
                        'mes_issuer' => $post['Bulletin']['bul_issuer'],
                        'mes_sourse_id' => 'bulletin_'.$Bulletin->primaryKey,
                        'mes_flag' => 1,
                        'mes_template' => 'bulletin_issuer',
                        'mes_module' => 'bulletin',
                        'mes_class' => 1,
                    ];
                    Message::create($mes_data, 1);
                    //添加发布人通知
                    $mes_data = [
                        'mes_title' => $post["Bulletin"]["bul_title"],
                        'mes_release_user' => $post['Bulletin']['bul_releaseuser'],
                        'mes_issuer' => $post['Bulletin']['bul_issuer'],
                        'mes_sourse_id' => 'bulletin_'.$Bulletin->primaryKey,
                        'mes_flag' => 2,
                        'mes_template' => 'bulletin_release',
                        'mes_module' => 'bulletin',
                        'mes_class' => 2,
                    ];
                    Message::create($mes_data, 1);
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
            $Usermodel = new User();
            if(!empty($bulletininfo)){
                //获取当前通知通告的发布人签发人信息
                $issuer = $Usermodel->find()
                    ->select(['id','username','head_img'])
                    ->where(array('id' => $bulletininfo['bul_issuer']))
                    ->asArray()
                    ->one();
            }
            $releaseuser = $Usermodel->find()
                ->select(['id','username','head_img'])
                ->where(array('id' => yii::$app->user->id))
                ->asArray()
                ->one();
            //获取发布通知通报的审核人员信息
            $issuers = $Usermodel->getSameDepartmentIssuerByUser(yii::$app->user->id);
            return $this->render('bulletinform', [
                'Bulletin' => $bulletininfo,
                'issuers' => $issuers,
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
                $bul_ids = [$bul_id];
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
                $bul_ids_tmp = $Bulletin->find()->select('bul_id')->asArray()->all();
                $bul_ids = array();
                foreach($bul_ids_tmp as $item){
                    array_push($bul_ids, $item['bul_id']);
                }
                $count = $Bulletin->deleteAll();
                if($count > 0 ){
                    $message['status'] = 100;
                }else{
                    $message['status'] = 101;
                }
                break;
        }
        Message::Messagedelete($bul_ids, 'bulletin');
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
            ->where(['bul_id' => $bul_id])
            ->asArray()
            ->one();
        $bulletininfo['bul_issuer'] = json_decode($bulletininfo['bul_issuer']);
        if(in_array(yii::$app->user->id, $bulletininfo['bul_issuer'])){
            if(!empty($bul_id)){
                $attributes = [
                    'bul_isexamine' => $status == 'true' ? 2 : 3,
                    'bul_examinetime' => time(),
                    'bul_examine_user' => yii::$app->user->id
                ];
                $condition = "bul_id=:bul_id";
                $params = [':bul_id' => $bul_id];
                $count = $Bulletin->updateAll($attributes, $condition, $params);
                if($count > 0){
                    //修改审核消息的状态
                    Message::updatestatus('bulletin_'.$bul_id, 'true', yii::$app->user->id, $bulletininfo['bul_releaseuser']);
                    //添加审核通知（发布人）
                    $mes_data = [
                        'mes_title' => $bulletininfo["bul_title"],
                        'mes_release_user' => $bulletininfo['bul_releaseuser'],
                        'mes_issuer' => $bulletininfo['bul_issuer'],
                        'mes_sourse_id' => 'bulletin_'.$bulletininfo['bul_id'],
                        'mes_flag' => 3,
                        'mes_template' => 'bulletin_examinerelease',
                        'mes_module' => 'bulletin',
                        'mes_class' => 2,
                    ];
                    Message::create($mes_data, 1);
                    //添加审核通知（审核人）
                    $mes_data = [
                        'mes_title' =>$bulletininfo["bul_title"],
                        'mes_release_user' => $bulletininfo['bul_releaseuser'],
                        'mes_issuer' => $bulletininfo['bul_issuer'],
                        'mes_sourse_id' => 'bulletin_'.$bulletininfo['bul_id'],
                        'mes_flag' => 4,
                        'mes_template' => 'bulletin_examineissuer',
                        'mes_module' => 'bulletin',
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
