<?php

// 引入 composer入口文件
include '../' . '/vendor/autoload.php';

use EasyWeChat\Foundation\Application;

// 微信端配置文件
$options = require("../" . 'config/wechatconf.php');

$app = new Application($options);
$server = $app->server;
$server->setMessageHandler(function ($message) {
    switch ($message->MsgType) {
        case 'event':
            // 事件消息...
            switch ($message->Event) {
                case 'subscribe':
                    return "您好！欢迎关注我!";
                    break;
                default:
                    return "其他";
                    break;
            }
            break;
        case 'text':
            // 文字消息...
            return "文字消息...";
            break;
        case 'image':
            // 图片消息...
            return "图片消息...";
            break;
        case 'voice':
            // 语音消息...
            return "语音消息...";
            break;
        case 'video':
            // 视频消息...
            return "视频消息...";
            break;
        case 'location':
            // 坐标消息...
            return "坐标消息...";
            break;
        case 'link':
            // 链接消息...
            return "链接消息...";
            break;
        default:
            // 其它消息... 
            return "其它消息...";
            break;
    }
});

// 将响应输出
$app->server->serve()->send();

?>