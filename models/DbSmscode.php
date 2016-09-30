<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%db_smscode}}".
 *
 * @property integer $id
 * @property string $s_phone
 * @property string $s_smscode
 * @property integer $s_smsaddtime
 * @property integer $s_status
 */
class DbSmscode extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%verification}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['v_userphone'], 'required'],
            [['v_type', 'v_status'], 'integer'],
            [['v_code'], 'string', 'max' => 11],
            [['v_time'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'v_id' => '自增长id',
            'se_id' => '安全日志id',
            'v_userphone' => '手机号码',
            'v_code' => '短信验证码',
            'v_time' => '添加时间',
            'v_status' => '使用状态1未使用2已使用',
            'v_type' => '验证码类别（1 注册，2忘记密码找回，3充值卡兑换 , 4 消费）',
        ];
    }
}
