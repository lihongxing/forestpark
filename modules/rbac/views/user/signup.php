<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = Yii::t('rbac-admin', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="/resource/js/lib/jquery-ui-1.10.3.min.js"></script>
<script language="javascript" src="/api/nestable/jquery.nestable.js"></script>
<link rel="stylesheet" type="text/css" href="/api/nestable/nestable.css"/>
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
                    <li class="active"><a href="#activity" data-toggle="tab">新增用户</a></li>
                    <li><a href="#timeline" data-toggle="tab">新增部门</a></li>
                    <li><a href="#settings" data-toggle="tab">信息查询</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="activity">
                        <form class="form-horizontal" id="signup" method="post" action="<?=\yii\helpers\Url::toRoute('/rbac/user/signup')?>">
                            <div class="box-body">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="username">用户名</label>
                                    <div class="col-sm-10">
                                        <input type="text" placeholder="请输入用户名" id="username" name="Signup[username]"
                                               value="" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="email">邮箱</label>

                                    <div class="col-sm-10">
                                        <input type="text" placeholder="请输入邮箱" name="Signup[email]" value="" id="email"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="phone">手机号码</label>

                                    <div class="col-sm-10">
                                        <input type="text" placeholder="请输入手机号码" id="mobile" name="Signup[mobile]"
                                               value="" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputPassword">新密码</label>

                                    <div class="col-sm-10">
                                        <input type="password" name="Signup[password]" placeholder="请输入新密码" id="password"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputPassword">确认密码</label>

                                    <div class="col-sm-10">
                                        <input type="password" name="Signup[retypePassword]" placeholder="请再次输入新密码" id="password1"
                                               class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">所属部门</label>
                                    <div class="col-sm-10 col-xs-12">
                                        <div class="input-group">
                                            <select name="groupid" class="form-control" id="groupid">
                                                <option value="0">请选择所属部门</option>
                                                <?php if(!empty($departments)){?>
                                                    <?php foreach($departments as $key => $item){?>
                                                        <option value="<?=$item['dep_id']?>"><?=$item['dep_name']?></option>
                                                    <?php }?>
                                                <?php }?>
                                            </select>
                                            <div class="input-group-btn">
                                                <a class="btn btn-default"><i class="fa fa-plus"></i> 新增部门</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <input type="hidden" name="id" value="">
                                <input type="hidden" name="_csrf" value="<?=yii::$app->request->getCsrfToken()?>">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="">头像</label>
                                    <div class="col-sm-10">
                                        <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'thumb', 'type'=>'thumb', 'value' => '', 'default' => '', 'options' => array('width' => 400, 'extras' => array('text' => 'ng-model="entry.thumb" class = "form-control ignore"'),'module' => 'admin')]) ?>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button class="btn btn-primary pull-right" type="submit">确认添加</button>
                            </div><!-- /.box-footer -->
                        </form>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">
                        <form class="form-horizontal" id="department" method="post" action="<?=\yii\helpers\Url::toRoute('/rbac/user/department-form')?>">
                            <div class="panel-body table-responsive" style="padding:0px;">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width:50px;"></th>
                                        <th>部门名称</th>
                                    </tr>
                                    </thead>
                                    <tbody id="param-items" class="ui-sortable" style="">
                                    <?php if(!empty($departments)){?>
                                        <?php foreach($departments as $key => $item){?>
                                            <tr>
                                                <td>
                                                    <a href="javascript:;" class="fa fa-move" title="拖动调整此显示顺序"><i class="fa fa-arrows"></i></a>&nbsp;
                                                    <a href="javascript:;" onclick="deleteParam(this)" style="margin-top:10px;" title="删除"><i class="fa fa-times"></i></a>
                                                </td>
                                                <td>
                                                    <input name="param_title[<?=$item['dep_id']?>]" class="form-control param_title" value="<?=$item['dep_name']?>" type="text">
                                                    <input name="param_id[<?=$item['dep_id']?>]" class="form-control" value="<?=$item['dep_id']?>" type="hidden">
                                                </td>
                                            </tr>
                                        <?php }?>
                                    <?php }?>
                                    </tbody>
                                    <tbody>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td colspan="2">
                                            <a href="javascript:;" id="add-param" onclick="addParam()" style="margin-top:10px;" class="btn btn-default" title="" data-original-title="添加属性"><i class="fa fa-plus"></i> 添加部门</a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="form-group col-sm-12">
                                    <input name="submit" value="提交" class="btn btn-primary col-lg-1" onclick="return formcheck()" data-original-title="" title="" type="submit">
                                    <input name="_csrf" value="<?=yii::$app->request->getCsrfToken()?>" type="hidden">
                                </div>
                            </div>
                            <script language="javascript">
                                $(function() {
                                    $("#param-items").sortable({handle: '.fa-move'});
                                    $("#chkoption").click(function() {
                                        var obj = $(this);
                                        if (obj.get(0).checked) {
                                            $("#tboption").show();
                                            $(".trp").hide();
                                        }
                                        else {
                                            $("#tboption").hide();
                                            $(".trp").show();
                                        }
                                    });
                                })
                                function addParam() {
                                    var url = "<?=\yii\helpers\Url::toRoute('/rbac/user/department-form')?>";
                                    $.ajax({
                                        "url": url,
                                        success: function(data) {
                                            $('#param-items').append(data);
                                        }
                                    });
                                    return;
                                }
                                function deleteParam(o) {
                                    $(o).parent().parent().remove();
                                }
                            </script>
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
    $().ready(function () {
        require(["validation", "validation-methods"], function (validate) {
            $("#signup").validate({
                ignore: ".ignore",
                rules: {
                    "Signup[username]": {
                        required: true,
                        remote:{
                            url:"<?=\yii\helpers\Url::toRoute('/rbac/user/check-attribute')?>",//后台处理程序
                            data:{
                                _csrf:function(){
                                    return $("#csrftoken").val();
                                }
                            },
                            type:"post",
                        },
                    },
                    "Signup[email]": {
                        required: true,
                        email:true,
                        remote:{
                            url:"<?=\yii\helpers\Url::toRoute('/rbac/user/check-attribute')?>",//后台处理程序
                            data:{
                                _csrf:function(){
                                    return $("#csrftoken").val();
                                }
                            },
                            type:"post",
                        },
                    },
                    "Signup[mobile]": {
                        required: true,
                        isMobile: true,
                        remote:{
                            url:"<?=\yii\helpers\Url::toRoute('/rbac/user/check-attribute')?>",//后台处理程序
                            data:{
                                _csrf:function(){
                                    return $("#csrftoken").val();
                                }
                            },
                            type:"post",
                        },
                    },
                    "Signup[password]": {
                        required: true,
                        minlength: 6,
                        maxlength: 30,
                    },
                    "Signup[retypePassword]": {
                        required: true,
                        equalTo: "#password"
                    },
                },
                messages: {
                    "Signup[username]": {
                        required: "请输入用户名",
                        remote: "用户名已存在",
                    },
                    "Signup[email]": {
                        required: "请输入邮箱",
                        email: "请输入正确的邮箱",
                        remote: "邮箱已存在",
                    },
                    "Signup[mobile]": {
                        required: "请输入手机号码",
                        isMobile: "请输入正确的手机号码",
                        remote: "手机号码已存在",
                    },
                    "Signup[password]": {
                        required: "请输入新密码",
                        minlength: "密码不能小于6个字符",
                        maxlength: "密码不能大于30个字符",

                    },
                    "Signup[retypePassword]": {
                        required: "请输入确认密码",
                        equalTo: "密码输入不一致",
                    },
                },
                errorClass: "has-error",
            });
        });
    });


</script>


