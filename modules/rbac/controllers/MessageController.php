<?php
/**
 * 站内信增删改查等
 * User: lihongxing
 * Date: 2016/10/14
 * Time: 10:07
 */

namespace app\modules\rbac\controllers;

use app\common\base\AdminbaseController;
use app\common\core\GlobalHelper;
use app\models\Admin;
use app\models\Message;
use yii\db\Query;
use yii;

class MessageController extends AdminbaseController
{

    /**
     * 查看消息的详细信息
     * @param $mes_id 消息的id
     * @return Json ststus：请求状态，mesinfo：消息的详细信息 url：审核信息的url连接
     */
    public function actionDetails()
    {
        $mes_id = yii::$app->request->post('mes_id');
        if(!empty($mes_id)){
            $query= new Query();
            $mesinfo = $query->select(['message.*', 'admin.*'])
                ->from(Message::tableName() . 'as message')
                ->leftJoin(Admin::tableName() . ' as admin', 'admin.id = message.mes_release_user')
                ->where(['message.mes_id' => $mes_id])
                ->one();
            $mesinfo['mes_addtime'] = GlobalHelper::format_date_distance_now(date("Y-m-d H:i:s",$mesinfo['mes_addtime']));

            //更改非审核信息的状态
            if($mesinfo['mes_class'] == 2){
                Message::updateAll(['mes_status' => 2], 'mes_id = :mes_id', [':mes_id' => $mes_id]);
            }
            echo json_encode([
                'status' => 100,
                'mesinfo' => $mesinfo,
                'url' => yii\helpers\Url::toRoute(["/admin/{$mesinfo['mes_module']}/{$mesinfo['mes_module']}-list",'id' => substr($mesinfo['mes_sourse_id'],strpos($mesinfo['mes_sourse_id'], '_')+1)])
            ]);
        }
    }

}