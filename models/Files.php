<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%upload}}".
 *
 * @property string $id
 * @property string $filename
 * @property string $attachment
 * @property integer $type
 * @property string $createtime
 */
class Files extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%upload}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['filename', 'attachment', 'type', 'createtime'], 'required'],
            [['type', 'createtime'], 'integer'],
            [['filename', 'attachment'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增长id',
            'filename' => '文件名称',
            'attachment' => '文件路径',
            'type' => '文件类型',
            'createtime' => '创建时间',
            'uniacid' => '模块名称'
        ];
    }
    
    /**
     * @保存图片到数据库
     * @param unknown $info
     */
    public function saveImage($info){
        $this->setattribute('filename',$info['filename']);
        $this->setattribute('attachment',$info['attachment']);
        $this->setattribute('type',$info['is_image']);
        $this->setattribute('createtime',time());
        $this->setattribute('uniacid', $info['uniacid']);
        $this->save(false);
    }
}
