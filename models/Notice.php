<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%notice}}".
 *
 * @property integer $not_id
 * @property string $not_name
 * @property string $not_title
 * @property string $not_content
 * @property integer $not_order
 * @property integer $not_show
 * @property integer $not_addtime
 * @property integer $not_updatetime
 * @property string $not_adduser
 */
class Notice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%notice}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['not_content'], 'string'],
            [['not_order', 'not_show', 'not_addtime', 'not_updatetime'], 'integer'],
            [['not_name', 'not_adduser'], 'string', 'max' => 128],
            [['not_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'not_id' => 'Not ID',
            'not_name' => 'Not Name',
            'not_title' => 'Not Title',
            'not_content' => 'Not Content',
            'not_order' => 'Not Order',
            'not_show' => 'Not Show',
            'not_addtime' => 'Not Addtime',
            'not_updatetime' => 'Not Updatetime',
            'not_adduser' => 'Not Adduser',
        ];
    }
}
