<?php
$this->title = Yii::t('rbac-admin', 'Useredit');
?>
<link href="/api/bootstrapswitch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
<script src="/api/bootstrapswitch/dist/js/bootstrap-switch.min.js"></script>
<style>
    .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
        background-color: #ffffff;
        border-bottom-color: #ffffff;
        color: #444;
        border-left-color: #f4f4f4;
        border-right-color: #f4f4f4;
        border-top-color: transparent;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#activity" data-toggle="tab">基本信息设置</a></li>
                    <li><a href="#timeline" data-toggle="tab">密码修改</a></li>
                    <li><a href="#settings" data-toggle="tab">信息查询</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                        <form class="form-horizontal" id="administratorsupdata" method="post" action="<?=\yii\helpers\Url::toRoute('/rbac/user/administratorsupdata')?>">
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputEmail3">用户名</label>

                                    <div class="col-sm-10">
                                        <input type="text" placeholder="请输入用户名" id="username" name="username"
                                               value="<?= $user->username ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputPassword3">邮箱</label>

                                    <div class="col-sm-10">
                                        <input type="text" placeholder="请输入邮箱" name="email" value="<?= $user->email ?>" id="email"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputPassword3">手机号码</label>

                                    <div class="col-sm-10">
                                        <input type="text" placeholder="请输入手机号码" id="mobile" name="mobile"
                                               value="<?= $user->mobile ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">所属部门</label>
                                    <div class="col-sm-10 col-xs-12">
                                        <select name="department" class="form-control" id="department">
                                            <option value="">请选择所属部门</option>
                                            <?php if(!empty($departments)){?>
                                                <?php foreach($departments as $key => $item){?>
                                                    <option <?php if($user->department == $item['dep_id'] ){echo 'selected';}?> value="<?=$item['dep_id']?>"><?=$item['dep_name']?></option>
                                                <?php }?>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="<?=$user->id?>">
                                <input type="hidden" name="_csrf" value="<?=yii::$app->request->getCsrfToken()?>">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="">头像</label>
                                    <div class="col-sm-10">
                                        <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'thumb','type' => 'thumb', 'value' => $user->head_img, 'default' => '', 'options' => array('width' => 400, 'extras' => array('text' => 'ng-model="entry.thumb" class = "form-control ignore"'),'module' => 'admin')]) ?>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button class="btn btn-primary pull-right" type="submit">确认修改</button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">
                        <form class="form-horizontal" id="passwordreset" action="<?=\yii\helpers\Url::toRoute(['/rbac/user/reset-password','token' => $user->password_reset_token])?>" method="post">
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputPassword">新密码</label>

                                    <div class="col-sm-10">
                                        <input type="password" name="ResetPassword[password]" placeholder="请输入新密码" id="password1"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputPassword">确认密码</label>

                                    <div class="col-sm-10">
                                        <input type="password" name="ResetPassword[retypePassword]" placeholder="请再次输入新密码" id="password2"
                                               class="form-control">
                                    </div>
                                </div>
                                <input type="hidden" name="_csrf" id="token"
                                       value="<?= yii::$app->request->getCsrfToken() ?>">
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button class="btn btn-primary pull-right" type="submit">确认修改</button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="settings">
                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
        </div>
</section>
<script type="text/javascript">
    $(function (argument) {
        $('[name="userstatus"]').bootstrapSwitch();
    })
    $().ready(function () {
        require(["validation", "validation-methods"], function (validate) {
            $("#passwordreset").validate({
                rules: {
                    "ResetPassword[password]": {
                        required: true,
                        minlength: 6,
                        maxlength: 30,
                    },
                    "ResetPassword[retypePassword]": {
                        required: true,
                        equalTo: "#password1"
                    },
                },
                messages: {
                    "ResetPassword[password]": {
                        required: "请输入新密码",
                        minlength: "密码不能小于6个字符",
                        maxlength: "密码不能大于30个字符",

                    },
                    "ResetPassword[retypePassword]": {
                        required: "请输入确认密码",
                        equalTo: "密码输入不一致",
                    },
                },
                errorClass: "has-error",
            });

            $("#administratorsupdata").validate({
                ignore: ".ignore",

                rules: {
                    "username": {
                        required: true,
                    },
                    "email": {
                        required: true,
                        email:true
                    },
                    "mobile": {
                        required: true,
                        isMobile: true,
                    }
                },
                messages: {
                    "username": {
                        required: "请输入用户名",
                    },
                    "email": {
                        required: "请输入邮箱",
                        email: "请输入正确的邮箱"
                    },
                    "mobile": {
                        required: "请输入手机号码",
                        isMobile: "请输入正确的手机号码",
                    }
                },
                errorClass: "has-error",
            });
        });


    });
</script>