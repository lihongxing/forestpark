<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= yii::$app->params['siteinfo']['sitename'] ?> | 登陆</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="/admin/plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">
<?php $this->beginBody() ?>
<div class="login-box">
    <div class="login-logo">
        <a href="/admin/index2.html"><b><?= yii::$app->params['siteinfo']['sitename'] ?></b></a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">用户登录</p>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
        ]); ?>
        <div class="form-group has-feedback">
                <input type="text" class="form-control" name="Login[username]" placeholder="请输入用户名">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" name="Login[password]" placeholder="请输入密码">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input name="Login[rememberMe]" type="checkbox" value="0"> 记住我
                        </label>
                    </div>
                </div><!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">登陆</button>
                </div><!-- /.col -->
            </div>
            <input type="hidden" name="_csrf" value="<?= yii::$app->request->csrfToken ?>"/>
        <?php ActiveForm::end(); ?>
        <!--
        <a href="#">忘记密码</a><br>
        <a href="register.html" class="text-center">注册新会员</a>-->
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="/admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="/admin/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="/admin/plugins/iCheck/icheck.min.js"></script>
<!-- validate -->
<script src="/api/validate/dist/jquery.validate.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
    $().ready(function() {
        $("#login-form").validate({
            //定义错误信息的显示位置
            errorPlacement: function(error, element) {
                error.appendTo( element.next().parent() );
            },
            rules: {
                "Login[username]": {
                    required: true,
                },
                "Login[password]": {
                    required: true,
                    minlength: 6,
                    maxlength: 16
                }
            },
            messages: {
                "Login[username]": {
                    required: "请输入用户名！",
                },
                "Login[password]": {
                    required: "请输入用户密码！",
                    minlength: "请输入6-16位，区分大小写！",
                    maxlength: "请输入6-16位，区分大小写！",
                }
            },
            //定义错误信息的样式
            errorClass: "has-error",
        });
    });
</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
