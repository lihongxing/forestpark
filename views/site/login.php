<?php
use yii\helpers\Url;

?>
<? require(\Yii::getAlias("@app") . "/views/layouts------/header-base.php"); ?>
<style>
    @media screen and (max-width: 767px) {
        .login .panel.panel-default {
            width: 90%;
            min-width: 300px;
        }
    }

    @media screen and (min-width: 768px) {
        .login .panel.panel-default {
            width: 70%;
        }
    }

    @media screen and (min-width: 1200px) {
        .login .panel.panel-default {
            width: 50%;
        }
    }
</style>
<div class="login">
    <div class="logo">
        <a href="./?refresh"
           style="background:url('{php echo tomedia($_W['setting']['copyright']['flogo']);}') no-repeat;"></a>
    </div>
    <div class="clearfix" style="margin-bottom:5em;">
        <div class="panel panel-default container">
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="form-group input-group">
                    <div class="input-group-addon"><i class="fa fa-user"></i></div>
                    <input name="username" type="text" class="form-control input-lg" placeholder="请输入用户名登录">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-addon"><i class="fa fa-unlock-alt"></i></div>
                    <input name="password" type="password" class="form-control input-lg" placeholder="请输入登录密码">
                </div>
                <div class="form-group">
                    <label class="checkbox-inline input-lg">
                        <input type="checkbox" value="true" name="rember"> 记住用户名 <font color="red"></font>
                    </label>
                    <div class="pull-right">
                        <a href="<?= Url::toRoute("/site/register") ?>" class="btn btn-link btn-lg">注册</a>
                        <input type="submit" name="submit" value="登录" class="btn btn-primary btn-lg"/>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="center-block footer" role="footer">
        <div class="text-center">
            <a href="http://www.we7.cc">关于微擎</a>&nbsp;&nbsp;<a href="http://bbs.we7.cc">微擎论坛</a>&nbsp;&nbsp;<a
                href="http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzkzODAwMzEzOV8xNzEwOTZfNDAwMDgyODUwMl8yXw">联系客服</a>
        </div>
        <div class="text-center">
            <a href="http://www.we7.cc"><b>微擎</b></a> 2014-2015 <a href="http://www.we7.cc">www.we7.cc</a>
        </div>
    </div>
</div>
<script>
    function formcheck() {
        if ($('#remember:checked').length == 1) {
            cookie.set('remember-username', $(':text[name="username"]').val());
        } else {
            cookie.del('remember-username');
        }
        return true;
    }
    var h = document.documentElement.clientHeight;
    $(".login").css('min-height', h);
</script>
</body>
</html>