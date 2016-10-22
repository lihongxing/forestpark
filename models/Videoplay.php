<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%videoplay}}".
 *
 * @property integer $vid_id
 * @property string $vid_title
 * @property string $vid_describe
 * @property string $vid_url
 * @property integer $vid_release_uid
 * @property integer $vid_ examine_uid
 * @property integer $vid_addtime
 * @property integer $vid_examine_time
 * @property integer $vid_examine_status
 */
class Videoplay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%videoplay}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['vid_describe'], 'string'],
            [['vid_release_uid', 'vid_examine_uid', 'vid_addtime', 'vid_examine_time', 'vid_examine_status'], 'integer'],
            [['vid_title', 'vid_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'vid_id' => '自增长id',
            'vid_title' => '视屏的标题',
            'vid_describe' => '视屏播放的描述',
            'vid_url' => '视屏连接',
            'vid_release_uid' => '视屏播放的发布人',
            'vid_examine_uid' => '视频播放的审核人',
            'vid_addtime' => '发布的时间',
            'vid_examine_time' => '视屏播放的审核时间',
            'vid_examine_status' => '视屏播放的审核状态',
        ];
    }
}
