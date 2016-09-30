<?php
use yii\helpers\Html;
$this->title = Yii::t('admin', 'navigtionadd');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'sitemanage'), 'url' => ['navigtion-list']];
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="/admin/plugins/iCheck/all.css">
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=$this->title ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <form action="" method="post" class="form-horizontal" role="form" enctype="multipart/form-data" id="form">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">导航名称</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="NavigationForm[nav_name]" class="form-control" value="<?=$NavigationForm['nav_name']?>" type="text" placeholder="请输入导航名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">导航标题</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="NavigationForm[nav_title]" class="form-control" value="<?=$NavigationForm['nav_title']?>" type="text" placeholder="请输入导航标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">导航链接</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="NavigationForm[nav_href]" class="form-control" value="<?=$NavigationForm['nav_href']?>" type="text" placeholder="请输入网站连接">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">父级导航</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="NavigationForm[nav_parent]" class="form-control" value="<?=$NavigationForm['nav_parent']?>" type="text" placeholder="请输入父级导航">
                                <span class="help-block">如果没有选择父级导航则默认自己为父级导航。</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">导航排序</label>
                            <div class="col-sm-9">
                                <input class="form-control" value="1" placeholder="请输入导航排序" type="number" min="1" max="99" name="NavigationForm[nav_order]" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">导航是否显示</label>
                            <div class="col-sm-9">
                                <label>
                                    <input type="radio" name="NavigationForm[nav_show]" value='1' class="minimal-blue" <?php if($NavigationForm[nav_show] == 1){echo 'checked'; }?> >显示
                                </label>
                                <label>
                                    <input type="radio" name="NavigationForm[nav_show]" value="0" class="minimal-blue" <?php if($NavigationForm[nav_show] == 0){echo 'checked'; }?>>隐藏
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-1 col-xs-12 col-sm-9 col-md-10 col-lg-21">
                                <input type="submit" class="btn btn-primary col-lg-1" value="<?=empty($NavigationForm['nav_id'])? '新增':'修改'?>" name="add" id="add" data-original-title="" title="">
                                <input type="hidden" value="<?=yii::$app->request->getCsrfToken()?>" name="_csrf">
                                <input type="hidden" value="<?=$NavigationForm['nav_id']?>" name="nav_id">
                                <input type="button" class="btn btn-default col-lg-2" value="返回列表"
                                       style="margin-left:10px;" onclick="history.back()" name="back">
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
</section>
<script>
    $(function () {
        require(["validation", "validation-methods"], function (validate) {
            $("#form").validate({
                rules: {
                    "NavigationForm[nav_name]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    "NavigationForm[nav_title]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    "NavigationForm[nav_href]": {
                        required: true,
                    },
                    "NavigationForm[nav_parent]": {
                        remote:{
                            url:"/admin/site/check-nav_parent.html",//后台处理程序
                            data:{
                                _csrf:function(){
                                    return "<?=yii::$app->request->getCsrfToken()?>";
                                }
                            },
                            type:"post",
                        }
                    }
                },
                messages: {
                    "NavigationForm[nav_name]": {
                        required: "请输入导航名称",
                        minlength: "导航名称不能小于2个字符",
                        maxlength: "导航名称不能大于30个字符",
                    },
                    "NavigationForm[nav_title]": {
                        required: "请输入导航标题",
                        minlength: "导航标题不能小于2个字符",
                        maxlength: "导航标题不能大于30个字符",
                    },
                    "NavigationForm[nav_href]": {
                        required: "请输入导航连接",
                    },
                    "NavigationForm[nav_parent]": {
                        remote: "您所输入的父级菜单不存在，请重新输入",
                    }
                },
                errorClass: "has-error",
            });
        });
    });
    $('input[type="checkbox"].minimal-blue, input[type="radio"].minimal-blue').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
</script>
