<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\rbac\models\Menu */

$this->title = Yii::t('rbac-admin', 'Update Menu');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Menus'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="/api/validate/dist/jquery.validate.js"></script>
<link rel="stylesheet" href="/admin/plugins/iCheck/all.css">
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form class="form-horizontal" id="addrole" method="post"
                      action="<?= \yii\helpers\Url::toRoute(['/rbac/menu/update', 'id' => $model->id]) ?>">
                    <div class="box-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputEmail3">菜单名称</label>
                            <div class="col-sm-10">
                                <input type="text" name="Menu[name]" placeholder="请输入菜单名称" value="<?= $model->name ?>"
                                       id="menu-name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">父级菜单</label>
                            <div class="col-sm-10">
                                <input type="text" name="Menu[parent_name]" placeholder="请输入父级名称"
                                       value="<?= $model->parent_name ?>" id="parent_name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">菜单链接</label>
                            <div class="col-sm-10">
                                <input id="menu-route" class="form-control" placeholder="请输入菜单链接"
                                       value="<?= $model->route ?>" type="text" name="Menu[route]" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">菜单排序</label>
                            <div class="col-sm-10">
                                <input id="menu-order" class="form-control" placeholder="请输入菜单菜单排序"
                                       value="<?= $model->order ?>" type="number" name="Menu[order]" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">菜单是否显示</label>
                            <div class="col-sm-10">
                                <label>
                                    <input type="radio" name="show" value='show' class="minimal-blue" <?= json_decode($model->data)->visible ? 'checked':'' ?>>显示
                                </label>
                                <label>
                                    <input type="radio" name="show" value="hidden" <?= json_decode($model->data)->visible ? '':'checked' ?> class="minimal-blue">隐藏
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">菜单默认图标</label>
                            <div class="col-sm-10">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'icon', 'type' => 'icon', 'value' => json_decode($model->data)->icon]) ?>
                            </div>
                        </div>
                        <input type="hidden" name="_csrf" value="<?= yii::$app->request->getCsrfToken() ?>">
                        <div class="box-footer">
                            <button class="btn btn-primary pull-right" type="submit">确认修改</button>
                        </div><!-- /.box-footer -->
                </form>
            </div><!-- /.box -->
</section>
<script type="text/javascript">
    $(function () {
        $("#addrole").validate({
            rules: {
                "Menu[name]": {
                    required: true,
                    minlength: 3,
                    maxlength: 30
                }
            },
            messages: {
                "Menu[name]": {
                    required: "请输入菜单名称",
                    minlength: "菜单名称不能小于3个字符",
                    maxlength: "菜单名称不能大于30个字符",
                }
            },
            errorClass: "has-error",
        });
    });


    $('input[type="checkbox"].minimal-blue, input[type="radio"].minimal-blue').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
</script>

