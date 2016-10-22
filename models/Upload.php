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
class Upload extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%picture}}';
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增长id',
            'filename' => '文件名称',
            'pic_path' => '文件路径',
            'pic_addtime' => '创建时间',
            'uniacid' => '模块名称'
        ];
    }

    /**
     * @保存图片到数据库
     * @param unknown $info
     */
    public function saveImage($info)
    {
        $this->setattribute('filename', $info['filename']);
        $this->setattribute('pic_addtime', time());
        $this->setattribute('pic_path', $info['attachment']);
        $this->setattribute('uniacid', $info['uniacid']);
        $count = $this->save(false);
        if ($count > 0) {
            return 100;
        } else {
            return 101;
        }
    }
}
