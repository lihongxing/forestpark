<?php

namespace app\modules\rbac;

use Yii;
class RbacClass extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\rbac\controllers';
    public function init()
    {
        parent::init();
        if (!isset(Yii::$app->i18n->translations['rbac-admin'])) {
            Yii::$app->i18n->translations['rbac-admin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'zh',
                'basePath' => '@app/modules/rbac/messages'
            ];
        }
        // 设置错误处理默认控制器
        \yii::$app->errorHandler->errorAction = "admin/site/error";
    }
}
