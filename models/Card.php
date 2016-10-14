<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "card".
 *
 * @property integer $id
 * @property string $card_id
 * @property integer $times
 * @property integer $status
 * @property integer $integral
 * @property string $password
 * @property integer $exchangetime
 * @property integer $createtime
 * @property integer $phone
 * @property string $username
 */
class Card extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['card_id'], 'required'],
            [['times', 'status', 'integral', 'exchangetime', 'createtime', 'phone'], 'integer'],
            [['card_id'], 'string', 'max' => 16],
            [['password'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 64],
            [['card_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'card_id' => 'Card ID',
            'times' => 'Times',
            'status' => 'Status',
            'integral' => 'Integral',
            'password' => 'Password',
            'exchangetime' => 'Exchangetime',
            'createtime' => 'Createtime',
            'phone' => 'Phone',
            'username' => 'Username',
        ];
    }
}
