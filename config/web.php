<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'zh-CN',//默认使用中文
    'components' => [
        // 路由的配置
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'suffix' => '.html',
            'rules' => require(__DIR__ . "/route.php")
        ],
        'request' => [
            'cookieValidationKey' => 'lianqicms',
        ],
        // membercache缓存配置
        'membercache' => array(
            'class' => 'yii\caching\MemCache',
            'servers' => array(
                array(
                    'host' => '127.0.0.1',
                    'port' => 11211,
                )
            ),
        ),
        //文件缓存
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'keyPrefix' => 'myapp',       // 唯一键前缀
        ],
        //数据库缓存配置
        'dbcache' => [
            'class' => 'yii\caching\DbCache',
        ],

        'rediscache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 0,
            ]
        ],

        // 前台用户组件
        'user' => [
            'identityClass' => 'app\modules\rbac\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '__user_identity', 'httpOnly' => true],
            'idParam' => '__user',
            'loginUrl' => ['site/login'],
        ],

        //后台用户组件
        'admin' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\models\Admin',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '__Admin_identity', 'httpOnly' => true],
            'idParam' => '__Admin',
            'loginUrl' => ['admin/login/login'],
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error'],
                    'logFile' => '@app/runtime/logs/' . date("Ymd", time()) . 'error.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['info'],
                    'logFile' => '@app/runtime/logs/' . date("Ymd", time()) . 'info.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['warning'],
                    'logFile' => '@app/runtime/logs/' . date("Ymd", time()) . 'warning.log',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // 使用数据库管理配置文件
            'defaultRoles' => ['/rbac/user/login'],
        ],
        'as access' => [
            'class' => 'app\modules\rbac\components\AccessControl',
            'allowActions' => [
                //'*',//允许访问的节点，可自行添加
                //'*',//允许所有人访问admin节点及其子节点
            ]
        ],
        //设置不加载框架jquery
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => []
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],

    // 模块配置
    'modules' => [
        // 后台模块的配置
        'admin' => [
            'class' => 'app\modules\admin\AdminClass',
        ],
        'rbac' => [
            'class' => 'app\modules\rbac\RbacClass',
            'layout' => '@app/modules/admin/views/layouts/main.php',//yii2-admin的导航菜单
        ],
    ],
    'name' => $params['siteinfo']['sitename'],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
