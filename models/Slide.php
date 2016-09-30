<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%slide}}".
 *
 * @property integer $sli_id
 * @property string $sli_pic
 * @property string $sli_des
 * @property string $sli_title
 * @property string $sli_url
 */
class Slide extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%slide}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sli_pic', 'sli_des', 'sli_title', 'sli_url'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sli_id' => 'Sli ID',
            'sli_pic' => 'Sli Pic',
            'sli_des' => 'Sli Des',
            'sli_title' => 'Sli Title',
            'sli_url' => 'Sli Url',
        ];
    }
}
