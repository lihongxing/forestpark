<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\rbac\models\Menu */

$this->title = Yii::t('admin', 'sitebasicinformation');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'navigtionmanage'), 'url' => ['index']];
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
                    <h3 class="box-title"><?=$this->title ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <form action="" method="post" class="form-horizontal" role="form" enctype="multipart/form-data" id="form1">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">网站名称</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[sitename]" class="form-control" value="<?=$settings['sitename']?>" type="text" placeholder="网站名称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">网站简称</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[sitenameabbreviat]" class="form-control" value="<?=$settings['sitenameabbreviat']?>" type="text" placeholder="网站简称">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">网站标题</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[sitetitle]" class="form-control" value="" type="text" placeholder="网站标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">网站URL</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[siteurl]" class="form-control" value="<?=$settings['siteurl']?>" type="text" placeholder="网站URL">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">平台域名</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[sitehost]" class="form-control" value="<?=$settings['sitehost']?>" type="text" placeholder="平台域名">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">网站关键词</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[keywords]" class="form-control" value="<?=$settings['keywords']?>" type="text" placeholder="网站关键词">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">网站描述</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[description]" class="form-control" value="<?=$settings['description']?>" type="text" placeholder="网站描述">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">favorite icon</label>
                            <div class="col-sm-10 col-xs-12">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'settings[icon]', 'type' => 'thumb', 'value' => $settings['icon'], 'default' => '', 'options' => array('global' => true, 'extras' => array('image'=> ' width="32" ','text' => 'ng-model="entry.thumb" class = "form-control ignore"'),'module' => 'admin')]) ?>
                                <span class="help-block">favorite icon</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">前台LOGO</label>
                            <div class="col-sm-10 col-xs-12">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'settings[logo]', 'type' => 'thumb', 'value' => $settings['logo'], 'default' => '', 'options' => array('width' => 400, 'global' => true, 'extras' => array('text' => 'ng-model="entry.thumb" class = "form-control ignore"'),'module' => 'admin')]) ?>
                                <span class="help-block">此logo是指首页及登录页面logo，建议尺寸 220x50。</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">联系人</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[person]" class="form-control" value="<?=$settings['person']?>" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">联系电话</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[phone]" class="form-control" value="<?=$settings['phone']?>" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">QQ</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[qq]" class="form-control" value="<?=$settings['qq']?>" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">邮箱</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[email]" class="form-control" value="<?=$settings['email']?>" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">详细地址</label>
                            <div class="col-sm-10 col-xs-12">
                                <input name="settings[address]" value="<?=$settings['address']?>" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-1 col-xs-12 col-sm-10 col-md-10 col-lg-21">
                                <input name="submit" value="提交" class="btn btn-primary span3" type="submit">
                                <input name="_csrf" value="<?=yii::$app->request->getCsrfToken()?>" type="hidden">
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
</section>
