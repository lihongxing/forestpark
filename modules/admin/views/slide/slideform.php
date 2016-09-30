<?php
use yii\helpers\Html;
$this->title = Yii::t('admin', 'slideadd');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'sitebuild'), 'url' => ['slide-list']];
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
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">幻灯片名称</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="Slide[sli_name]" class="form-control" value="<?=$Slide['sli_name']?>" type="text" placeholder="请输入幻灯片名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">幻灯片标题</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="Slide[sli_title]" class="form-control" value="<?=$Slide['sli_title']?>" type="text" placeholder="请输入幻灯片标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">幻灯片链接</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="Slide[sli_url]" class="form-control" value="<?=$Slide['sli_url']?>" type="text" placeholder="请输入网站连接">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">幻灯片排序</label>
                            <div class="col-sm-9">
                                <input class="form-control" value="1" placeholder="请输入幻灯片排序" type="number" min="1" max="99" name="Slide[sli_order]" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">幻灯片描述</label>
                            <div class="col-sm-9">
                                <textarea rows="6" name="Slide[sli_des]" placeholder="请输入幻灯片描述" class="form-control" ><?=$Slide['sli_des']?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="">幻灯片图片</label>
                            <div class="col-sm-9">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'Slide[sli_pic]', 'type'=>'thumb', 'value' => $Slide['sli_pic'], 'default' => '', 'options' => array('width' => 400, 'extras' => array('text' => 'ng-model="entry.thumb" class = "form-control ignore"'),'module' => 'admin')]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">幻灯片是否显示</label>
                            <div class="col-sm-9">
                                <label>
                                    <input type="radio" name="Slide[sli_show]" value='1' class="minimal-blue" <?php if($Slide[sli_show] == 1){echo 'checked'; }?> >显示
                                </label>
                                <label>
                                    <input type="radio" name="Slide[sli_show]" value="0" class="minimal-blue" <?php if($Slide[sli_show] == 0){echo 'checked'; }?>>隐藏
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-1 col-xs-12 col-sm-9 col-md-10 col-lg-21">
                                <input type="submit" class="btn btn-primary col-lg-1" value="<?=empty($Slide['sli_id'])? '新增':'修改'?>" name="add" id="add" data-original-title="" title="">
                                <input type="hidden" value="<?=yii::$app->request->getCsrfToken()?>" name="_csrf">
                                <input type="hidden" value="<?=$Slide['sli_id']?>" name="sli_id">
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
                ignore: ".ignore",
                rules: {
                    "Slide[sli_name]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    "Slide[sli_title]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    "Slide[sli_url]": {
                        required: true,
                    },
                },
                messages: {
                    "Slide[sli_name]": {
                        required: "请输入幻灯片名称",
                        minlength: "幻灯片名称不能小于2个字符",
                        maxlength: "幻灯片名称不能大于30个字符",
                    },
                    "Slide[sli_title]": {
                        required: "请输入幻灯片标题",
                        minlength: "幻灯片标题不能小于2个字符",
                        maxlength: "幻灯片标题不能大于30个字符",
                    },
                    "Slide[sli_url]": {
                        required: "请输入幻灯片连接",
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
