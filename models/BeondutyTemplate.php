<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%beonduty_template}}".
 *
 * @property integer $tem_id
 * @property integer $tem_uid
 * @property string $tem_username
 * @property string $tem_currColor
 */
class BeondutyTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%beonduty_template}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tem_id'], 'required'],
            [['tem_id', 'tem_uid'], 'integer'],
            [['tem_username'], 'string', 'max' => 64],
            [['tem_currColor'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tem_id' => 'Tem ID',
            'tem_uid' => 'Tem Uid',
            'tem_username' => 'Tem Username',
            'tem_currColor' => 'Tem Curr Color',
        ];
    }
}
