<?php
define('IA_ROOT', str_replace("\\", '/', dirname(dirname(__FILE__))));
define('IA_ROOT_DOWNLOAD', IA_ROOT . '/web/download');
$siteinfo = require(__DIR__ . "/siteinfo.php");
return [
    'siteinfo' => $siteinfo,
    'admin.passwordResetTokenExpire' => 3600,
];
