<?php

namespace app\models;

use app\modules\rbac\models\User;
use Yii;
use yii\validators\BooleanValidator;
use app\common\core\GlobalHelper;
use yii\base\Widget;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property integer $mes_id
 * @property integer $mes_addtime
 * @property string $mes_title
 * @property integer $mes_release_user
 * @property integer $mes_type
 * @property integer $mes_status
 * @property integer $mes_times
 * @property string $mes_handle_userinfo
 */
class Message extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mes_addtime', 'mes_title'], 'required'],
            [['mes_addtime', 'mes_release_user', 'mes_type', 'mes_status', 'mes_times'], 'integer'],
            [['mes_title', 'mes_handle_userinfo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'mes_id' => '自增长id',
            'mes_addtime' => '站内消息添加时间',
            'mes_title' => '消息的标题',
            'mes_release_user' => '发布人',
            'mes_type' => '消息类型 1：审核消息，2：查看浏览消息',
            'mes_status' => '消息状态 1：未审核，2：审核，3：未l浏览查看，4：已浏览查看',
            'mes_times' => '浏览查看的次数',
            'mes_handle_userinfo' => '审核或者浏览查看的用户信息',
        ];
    }

    /**
     * 新增站内消息方法
     * @user lihongxing
     * @param $title 站内消息标题
     * @param $mes_release_user 站内消息发布者
     * @param $mes_type 站内消息类型
     * @return Boolean $status
     */
    public Static function create($mes_data, $mes_type)
    {
        if ($mes_type == 1) {
            $Usermodel = new User();
            //mes_flag 1:A->B 2:A->A 3:B->A 4:B->B
            foreach ($mes_data['mes_issuer'] as $key => $mes_issuer) {
                $Messagemodel = new Message();
                $Messagemodel->setAttribute('mes_type', $mes_type);
                $Messagemodel->setAttribute('mes_addtime', time());
                if ($mes_data['mes_flag'] == 1) {
                    $release_userinfo = $Usermodel->find()
                        ->select(['username'])
                        ->asArray()
                        ->where(['id' => $mes_data['mes_release_user']])
                        ->one();
                    $Messagemodel->setAttribute('mes_release_user', $mes_data['mes_release_user']);
                    $Messagemodel->setAttribute('mes_issuer', $mes_issuer);
                    $release_username = $release_userinfo['username'];
                    $issuer_username = '您';
                } elseif ($mes_data['mes_flag'] == 2) {
                    $Messagemodel->setAttribute('mes_release_user', $mes_data['mes_release_user']);
                    $Messagemodel->setAttribute('mes_issuer', $mes_data['mes_release_user']);
                    $issuerinfo = $Usermodel->find()
                        ->select(['username'])
                        ->asArray()
                        ->where(['id' => $mes_issuer])
                        ->one();
                    $release_username = '您';
                    $issuer_username = $issuerinfo['username'];
                } elseif ($mes_data['mes_flag'] == 3) {
                    if ($mes_issuer != yii::$app->user - id) {
                        continue;
                    }
                    $Messagemodel->setAttribute('mes_release_user', $mes_issuer);
                    $Messagemodel->setAttribute('mes_issuer', $mes_data['mes_release_user']);
                    $issuerinfo = $Usermodel->find()
                        ->select(['username'])
                        ->asArray()
                        ->where(['id' => $mes_issuer])
                        ->one();
                    $release_username = '您';
                    $issuer_username = $issuerinfo['username'];
                } elseif ($mes_data['mes_flag'] == 4) {
                    $Messagemodel->setAttribute('mes_release_user', $mes_issuer);
                    $Messagemodel->setAttribute('mes_issuer', $mes_issuer);
                    $release_userinfo = $Usermodel->find()
                        ->select(['username'])
                        ->asArray()
                        ->where(['id' => $mes_data['mes_release_user']])
                        ->one();
                    $release_username = $release_userinfo['username'];
                    if ($mes_issuer == yii::$app->user->id) {
                        $issuer_username = '您';
                    } else {
                        $issuerinfo = $Usermodel->find()
                            ->select(['username'])
                            ->asArray()
                            ->where(['id' => yii::$app->user->id])
                            ->one();
                        $issuer_username = $issuerinfo['username'];
                    }

                }
                $mes_template = array(
                    'bulletin' => array(
                        'bulletin_release' => "{$release_username}发布标题为{$mes_data["mes_title"]}需要{$issuer_username}审核！",
                        'bulletin_issuer' => "{$release_username}发布标题为{$mes_data["mes_title"]}需要{$issuer_username}审核！",
                        'bulletin_examineissuer' => "{$release_username}发布标题为{$mes_data["mes_title"]}公告{$issuer_username}审核完成！",
                        'bulletin_examinerelease' => "{$release_username}发布标题为{$mes_data["mes_title"]}公告{$issuer_username}审核完成！",
                    ),
                    'videoplay' => array(
                        'videoplay_release' => "{$release_username}发布标题为{$mes_data["mes_title"]}视屏播放需要{$issuer_username}审核！",
                        'videoplay_issuer' => "{$release_username}发布标题为{$mes_data["mes_title"]}视屏播放需要{$issuer_username}审核！",
                        'videoplay_examineissuer' => "{$release_username}发布标题为{$mes_data["mes_title"]}视屏播放{$issuer_username}审核完成！",
                        'videoplay_examinerelease' => "{$release_username}发布标题为{$mes_data["mes_title"]}视屏播放{$issuer_username}审核完成！",
                    )
                );
                $mes_content = $mes_template[$mes_data['mes_module']][$mes_data['mes_template']];
                $Messagemodel->setAttribute('mes_title', $mes_data['mes_title']);
                $Messagemodel->setAttribute('mes_content', $mes_content);
                $Messagemodel->setAttribute('mes_status', 1);
                $Messagemodel->setAttribute('mes_class', $mes_data['mes_class']);
                $Messagemodel->setAttribute('mes_sourse_id', $mes_data['mes_sourse_id']);
                $Messagemodel->setAttribute('mes_module', $mes_data['mes_module']);
                $Messagemodel->save();

            }
            $result = true;
        } elseif ($mes_type == 2) {
            $Messagemodel = new Message();
            $Messagemodel->setAttribute('mes_title', $mes_data['mes_title']);
            $Messagemodel->setAttribute('mes_status', 3);
            $Messagemodel->setAttribute('mes_content', $mes_data['mes_content']);
            $result = true;
            if ($Messagemodel->save()) {
                $result = false;
            }
        }
        return $result;
    }


    /**
     * 修改站内信息的状态
     * @param $mes_sourse_id 站内信息的来源id
     * @param $status 站内信息状态
     */
    public static function updatestatus($mes_sourse_id, $status, $mes_release_user)
    {
        if (!empty($mes_sourse_id)) {
            $attributes = [
                'mes_status' => $status == 'true' ? 2 : 1
            ];
            $condition = "mes_sourse_id = :mes_sourse_id  AND mes_release_user = :mes_release_user";
            $params = [
                ':mes_sourse_id' => $mes_sourse_id,
                ':mes_release_user' => $mes_release_user
            ];
            $Messagemodel = new Message();
            $Messagemodel->updateAll($attributes, $condition, $params);
        }
    }


    public static function Messagedelete($mes_ids, $module)
    {
        if (!empty($mes_ids)) {
            $Messagemodel = new Message();
            foreach ($mes_ids as $key => &$item) {
                $item = $module . '_' . $item;
            }
            $Messagemodel->deleteAll(['in', 'mes_sourse_id', $mes_ids]);
        }
    }


    public function getMessageList($nums = '')
    {

        $message2s = Message::find()
            ->where(['mes_type' => 2, 'mes_status' => 3])
            ->orderBy('mes_addtime DESC')
            ->asArray()
            ->all();
        $message1s = Message::find()
            ->where(['mes_type' => 1, 'mes_status' => 1, 'mes_issuer' => \Yii::$app->user->id])
            ->orderBy('mes_addtime DESC')
            ->asArray()
            ->all();
        $count1 = Message::find()
            ->where(['mes_type' => 2, 'mes_status' => 3])
            ->count();
        $count2 = Message::find()
            ->where(['mes_type' => 1, 'mes_status' => 1, 'mes_issuer' => \Yii::$app->user->id])
            ->count();
        if (empty($nums)) {
            $messages = array_slice(GlobalHelper::array_sort(array_merge($message2s, $message1s), 'mes_addtime', 'desc'), 0);
        } else {
            $messages = array_slice(GlobalHelper::array_sort(array_merge($message2s, $message1s), 'mes_addtime', 'desc'), 0, $nums);
        }
        foreach ($messages as $key => &$item) {
            //获取发布人的信息
            $Usermodel = new User();
            $release_user = $Usermodel->find()
                ->where(['id' => $item['mes_release_user']])
                ->asArray()
                ->one();
            $item['mes_addtime'] = GlobalHelper::format_date_distance_now(date("Y-m-d H:i:s", $item['mes_addtime']));
            $item['release_user'] = $release_user;
        }
        return [
            'count1' => $count1,
            'messages' => $messages,
            'count2' => $count2,
        ];
    }
}
