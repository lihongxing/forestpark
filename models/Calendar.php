<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%calendar}}".
 *
 * @property integer $cal_id
 * @property string $cal_title
 * @property integer $cal_starttime
 * @property integer $cal_endtime
 * @property string $cal_url
 * @property integer $cal_is_allday
 * @property string $cal_color
 * @property integer $cal_uid
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%calendar}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cal_starttime', 'cal_endtime', 'cal_is_allday', 'cal_uid'], 'integer'],
            [['cal_title', 'cal_color'], 'string', 'max' => 128],
            [['cal_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cal_id' => '自增长id',
            'cal_title' => '标题',
            'cal_starttime' => '开始时间',
            'cal_endtime' => '结束时间',
            'cal_url' => 'url连接',
            'cal_is_allday' => '是否为全天',
            'cal_color' => '颜色',
            'cal_uid' => '用户id',
        ];
    }
}
