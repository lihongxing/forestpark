<?php
/**
 * User: lihongxing
 * Date: 2016/10/10
 * Time: 9:39
 */

namespace app\modules\admin\widgets;


use app\common\core\GlobalHelper;
use app\modules\rbac\models\User;
use yii\base\Widget;
use app\models\Message;
use Yii;

class Messages extends Widget
{
    /**
     * 默认执行初始化
     */
    public function init()
    {
        parent::init();
    }

    /**
     * 默认执行犯方法run
     */
    public function run()
    {
        $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/AdminLTE/adminlte/dist');
        $Messagemodel = new Message();
        $results = $Messagemodel->getMessageList(8);
        return $this->render('@app/modules/admin//views/layouts/header', [
            'messages' => $results['messages'],
            'directoryAsset' => $directoryAsset,
            'count1' => $results['count1'],
            'count2' => $results['count2'],
        ]);
    }
}