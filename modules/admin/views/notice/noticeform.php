<?php
use yii\helpers\Html;
$this->title = Yii::t('admin', 'noticeadd');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'sitebuild'), 'url' => ['notice-list']];
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
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">公告名称</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="Notice[not_name]" class="form-control" value="<?=$Notice['not_name']?>" type="text" placeholder="请输入公告名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">公告标题</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="Notice[not_title]" class="form-control" value="<?=$Notice['not_title']?>" type="text" placeholder="请输入公告标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">公告排序</label>
                            <div class="col-sm-9">
                                <input class="form-control" value="1" placeholder="请输入公告排序" type="number" min="1" max="99" name="Notice[not_order]" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">通告详情</label>
                            <div class="col-sm-9 col-xs-12">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'Notice[not_content]', 'type' => 'content', 'value' => $Notice['not_content'],'options' => array('width' => 827,'module' => 'admin')]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">添加时间</label>
                            <div class="col-sm-9 col-xs-12">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'Notice[not_updatetime]', 'type' => 'timestart', 'value' => !empty($item['timestart']) ? date('Y-m-d H:i',$item['timestart']) : date('Y-m-d H:i'),'options' => 1]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">公告链接</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="Notice[not_url]" class="form-control" value="<?=$Notice['not_url']?>" type="text" placeholder="请输入公告连接">
                                <span class="help-block">没有输入连接则为站内链接否则为外部连接。</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">公告是否显示</label>
                            <div class="col-sm-9">
                                <label>
                                    <input type="radio" name="Notice[not_show]" value='1' class="minimal-blue" <?php if($Notice[not_show] == 1){echo 'checked'; }?> >显示
                                </label>
                                <label>
                                    <input type="radio" name="Notice[not_show]" value="0" class="minimal-blue" <?php if($Notice[not_show] == 0){echo 'checked'; }?>>隐藏
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-1 col-xs-12 col-sm-9 col-md-10 col-lg-21">
                                <input type="submit" class="btn btn-primary col-lg-1" value="<?=empty($Notice['not_id'])? '新增':'修改'?>" name="add" id="add" data-original-title="" title="">
                                <input type="hidden" value="<?=yii::$app->request->getCsrfToken()?>" name="_csrf">
                                <input type="hidden" value="<?=$Notice['not_id']?>" name="not_id">
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
                    "Notice[not_name]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    "Notice[not_title]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                },
                messages: {
                    "Notice[not_name]": {
                        required: "请输入公告名称",
                        minlength: "公告名称不能小于2个字符",
                        maxlength: "公告名称不能大于30个字符",
                    },
                    "Notice[not_title]": {
                        required: "请输入公告标题",
                        minlength: "公告标题不能小于2个字符",
                        maxlength: "公告标题不能大于30个字符",
                    },
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
