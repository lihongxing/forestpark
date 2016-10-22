<?php
namespace app\models;

use yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use app\common\core\StringHelper;
use yii\data\Pagination;

/*HelpCenterForm 直接继承数据库活动记录操作类*/

class NavigationForm extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return '{{%navigation}}';
    }

    public function rules()
    {
        return [
            [['nav_title', 'nav_hot'], 'required']
        ];
    }

    /**
     * 得到在前台显示的导航栏
     */
    public function GetNavigation()
    {
        $navigation = $this->find()
            ->where(['nav_show' => 1])
            ->asArray()
            ->all();
        return $navigation;
    }
}

?>