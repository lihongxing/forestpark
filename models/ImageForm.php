<?php
namespace app\models;

use yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use app\common\core\StringHelper;
use yii\data\Pagination;

/*HelpCenterForm 直接继承数据库活动记录操作类*/

class ImageForm extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%picture}}';
    }

    public function rules()
    {
        return [
            [['pic_id', 'pic_addtime', 'pic_path'], 'required']
        ];
    }

    function getImageSuffix($imagetype)
    {
        $imgtype = substr($imagetype, 6);
        if ($imgtype == 'jpeg') {
            $suffix = '.jpg';
        } else if ($imgtype == 'png') {
            $suffix = '.png';
        } else if ($imgtype == 'gif') {
            $suffix = '.gif';
        } else {
            $suffix = '102';//图片格式不正确，请重新上传
        }
        return $suffix;
    }
    //根据商品id获得图片信息
}

?>