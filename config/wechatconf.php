<?php
return [
    /**
     * Debug 模式，bool 值：true/false
     *
     * 当值为 false 时，所有的日志都不会记录
     */
    'debug'  => true,
    
    /**
     * 账号基本信息，请从微信公众平台/开放平台获取
     */
    'app_id' => 'wx0828fb711b5e9c6a',
    'secret' => '41b4a149b8d66660ef28cbf0057b5d9b',
    'token' => 'lianqicms',
    
    /**
     * 日志配置
     * 
     * level: 日志级别, 可选为：debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => 'debug',
        'file' =>  __DIR__.'/tmp/waterserver'.date('Ymd').'.log'
    ],
    
    /**
     * OAuth 配置
     *
     * scopes：公众平台（snsapi_userinfo / snsapi_base），开放平台：snsapi_login
     * callback：OAuth授权完成后的回调页地址
     */
    'oauth' => [
        'scopes'   => ['snsapi_userinfo'],
        'callback' => '/examples/oauth_callback.php',
    ],
    
    /**
     * 微信支付
     */
    'payment' => [
        'merchant_id'        => 'your-mch-id',
        'key'                => 'key-for-signature',
        'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
        // 'device_info'     => '013467007045764',
        // 'sub_app_id'      => '',
        // 'sub_merchant_id' => '',
        // ...
    ]
];
