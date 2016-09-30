<?php

namespace app\modules\admin;

use Yii;
class AdminClass extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';

    public function init()
    {
        parent::init();
        if (!isset(Yii::$app->i18n->translations['admin'])) {
            Yii::$app->i18n->translations['admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'zh',
                'basePath' => '@app/modules/admin/messages'
            ];
        }
        // 设置错误处理默认控制器
        \yii::$app->errorHandler->errorAction = "admin/site/error";
    }
}
