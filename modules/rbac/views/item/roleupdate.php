<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\rbac\models\AuthItem */
/* @var $context app\modules\rbac\components\ItemController */

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('rbac-admin', 'Update ' . $labels['Item']) . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', $labels['Items']), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('rbac-admin', 'Update');
?>
<script src="/api/validate/dist/jquery.validate.js"></script>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=Yii::t('rbac-admin', 'Update Role');?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" id="addrole" method="post" action="<?=\yii\helpers\Url::toRoute(['/rbac/role/update', 'id' => $model->name])?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputEmail3">角色名称</label>
                            <div class="col-sm-10">
                                <input type="text" name="AuthItem[name]" placeholder="请输入角色名称" id="authitem-name" value="<?=$model->name?>" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">角色描述</label>
                            <div class="col-sm-10">
                                <textarea id="authitem-description" class="form-control"   placeholder="请输入角色描述" rows="2" name="AuthItem[description]"><?=$model->description?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">角色规则</label>
                            <div class="col-sm-10">
                                <input id="rule_name" class="form-control" value="<?=$model->ruleName?>" placeholder="请输入角色规则" type="text" name="AuthItem[ruleName]" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">角色数据</label>
                            <div class="col-sm-10">
                                <textarea rows="6" name="AuthItem[data]" placeholder="请输入角色数据" class="form-control" id="authitem-data"><?=$model->data?></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="_csrf" value="<?=yii::$app->request->getCsrfToken()?>">
                        <div class="box-footer">
                            <button class="btn btn-primary pull-right" type="submit">确认修改</button>
                        </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
            <div>
            </div>
</section>
<script type="text/javascript">
    $().ready(function() {
        $("#addrole").validate({
            rules: {
                "AuthItem[name]": {
                    required: true,
                    minlength: 3,
                    maxlength:30
                },
                "AuthItem[description]": {
                    required: true,
                }
            },
            messages: {
                "AuthItem[name]": {
                    required: "请输入角色名称",
                    minlength: "角色名称不能小于3个字符",
                    maxlength: "角色名称不能大于30个字符",
                },
                "AuthItem[description]": {
                    required: "请输入角色描述",
                }
            },
            errorClass:"has-error",
        });
    });
</script>


