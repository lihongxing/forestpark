<?php
use yii\helpers\Html;
$this->title = Yii::t('admin', 'bulletinadd');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'sitebuild'), 'url' => ['bulletin-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript" src="/admin/js/tooltipbox.js"></script>
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
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">通知通报标题</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="Bulletin[bul_title]" class="form-control" value="<?=$Bulletin['bul_title']?>" type="text" placeholder="请输入通知通报标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">通知通报承办单位</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="Bulletin[bul_undertakingunit]" class="form-control" value="<?=$Bulletin['bul_undertakingunit']?>" type="text" placeholder="请输入通知通报承办单位">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">通知通报详情</label>
                            <div class="col-sm-9 col-xs-12">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'Bulletin[bul_content]', 'type' => 'content', 'value' => $Bulletin['bul_content'],'options' => array('width' => 827,'module' => 'admin')]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">添加时间</label>
                            <div class="col-sm-9 col-xs-12">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'Bulletin[bul_addtime]', 'type' => 'timestart', 'value' => !empty($item['timestart']) ? date('Y-m-d H:i',$item['timestart']) : date('Y-m-d H:i'),'options' => 1]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">通知通报签发人</label>
                            <?php if(!empty($issuers)){?>
                                <?php foreach($issuers as $key => $issuer){?>
                                    <div class="col-sm-4">
                                        <input type="text" name="issuer[username][<?=$key?>]" maxlength="30" value="<?=$issuer['username']?>" id="issuerusername<?=$key?>" class="form-control" readonly />
                                        <input type="hidden" value="<?=$issuer['id']?>" id='bul_issuer><?=$key?>' name="Bulletin[bul_issuer][<?=$key?>]" class="form-control"  />
                                        <span id="issuerheadimg<?=$key?>" class='help-block'><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?=$issuer['head_img']?>"/></span>
                                    </div>
                                <?php }?>
                            <?php }?>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">通知通报排序</label>
                            <div class="col-sm-9">
                                <input class="form-control" value="<?=empty($Bulletin['bul_order']) ? 1:$Bulletin['bul_order'] ?>" placeholder="请输入通知通报排序" type="number" min="1" max="99" name="Bulletin[bul_order]" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">通知通报发布人</label>
                            <div class="col-sm-4">
                                <input type="text" name="releaseuser[username]" maxlength="30" value="<?=$releaseuser['username']?>" id="releaseuserusername" class="form-control" readonly />
                                <input type="hidden" value="<?=$releaseuser['id']?>" id='bul_releaseuser' name="Bulletin[bul_releaseuser]" class="form-control"  />
                                <span id="releaseuserheadimg" class='help-block'><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?=$releaseuser['head_img']?>"/></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-1 col-xs-12 col-sm-9 col-md-10 col-lg-21">
                                <input type="submit" class="btn btn-primary col-lg-1" value="<?=empty($Bulletin['bul_id'])? '新增':'修改'?>" name="add" id="add" data-original-title="" title="">
                                <input type="hidden" value="<?=yii::$app->request->getCsrfToken()?>" name="_csrf">
                                <input type="hidden" value="<?=$Bulletin['bul_id']?>" name="bul_id">
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
                    "Bulletin[bul_title]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    "Bulletin[bul_undertakingunit]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    "Bulletin[bul_issuer][0]": {
                        required: true,
                    },
                    "Bulletin[bul_issuer][1]": {
                        required: true,
                    },
                    "Bulletin[bul_releaseuser]": {
                        required: true,
                    },
                },

                messages: {
                    "Bulletin[bul_title]": {
                        required: "请输入通知通报标题",
                        minlength: "通知通报标题不能小于2个字符",
                        maxlength: "通知通报标题不能大于30个字符",
                    },
                    "Bulletin[bul_undertakingunit]": {
                        required: "请输入通知通报承办单位",
                        minlength: "通知通报承办单位不能小于2个字符",
                        maxlength: "通知通报承办单位不能大于30个字符",
                    },
                    "Bulletin[bul_issuer][0]": {
                        required: "请选择通知通报签发人",
                    },
                    "Bulletin[bul_issuer][1]": {
                        required: "请选择通知通报签发人",
                    },
                    "Bulletin[bul_releaseuser]": {
                        required: "请选择通知通报发布人",
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
