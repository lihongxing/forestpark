<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%bulletin}}".
 *
 * @property integer $bul_id
 * @property string $bul_number
 * @property string $bul_issuer
 * @property string $bul_undertakingunit
 * @property integer $bul_addtime
 * @property string $bul_title
 * @property string $bul_content
 * @property integer $bul_examinetime
 * @property integer $bul_isexamine
 * @property integer $bul_signtime
 * @property string $bul_signuser
 */
class Bulletin extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%bulletin}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bul_number', 'bul_issuer'], 'required'],
            [['bul_addtime', 'bul_examinetime', 'bul_isexamine'], 'integer'],
            [['bul_content'], 'string'],
            [['bul_number'], 'string', 'max' => 32],
            [['bul_issuer'], 'string', 'max' => 64],
            [['bul_undertakingunit'], 'string', 'max' => 128],
            [['bul_title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bul_id' => '自增长id',
            'bul_number' => '通报编号',
            'bul_issuer' => '签发人',
            'bul_undertakingunit' => '承办单位',
            'bul_addtime' => '发布时间',
            'bul_title' => '通报标题',
            'bul_content' => '通报内容',
            'bul_examinetime' => '审核时间',
            'bul_isexamine' => '是否审核',
            'bul_signtime' => '签收时间',
            'bul_signuser' => '签收人',
        ];
    }
}
