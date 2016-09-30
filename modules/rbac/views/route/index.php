<?php

use yii\helpers\Html;
use yii\helpers\Json;
use app\modules\rbac\AnimateAsset;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $routes [] */

$this->title = Yii::t('rbac-admin', 'Routes');
$this->params['breadcrumbs'][] = $this->title;

AnimateAsset::register($this);
$opts = Json::htmlEncode([
    'routes' => $routes
]);
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>
<script>
    var _opts = <?=$opts?>;
</script>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-sm-11">
            <div class="input-group">
                <input id="inp-route" type="text" class="form-control"
                       placeholder="<?= Yii::t('rbac-admin', 'New route(s)') ?>">
                <span class="input-group-btn">
                    <?= Html::a(Yii::t('rbac-admin', 'Add') . $animateIcon, ['create'], [
                        'class' => 'btn btn-primary',
                        'id' => 'btn-new'
                    ]) ?>
                </span>
            </div>
        </div>
    </div>
    <p>&nbsp;</p>
    <div class="row">
        <div class="col-sm-5">
            <div class="input-group">
                <input class="form-control search" data-target="avaliable"
                       placeholder="<?= Yii::t('rbac-admin', 'Search for avaliable') ?>">
                <span class="input-group-btn">
                    <?= Html::a('<span class="glyphicon glyphicon-refresh"></span>', ['refresh'], [
                        'class' => 'btn btn-default',
                        'id' => 'btn-refresh'
                    ]) ?>
                </span>
            </div>
            <select multiple size="20" class="form-control list" data-target="avaliable"></select>
        </div>
        <div class="col-sm-1">
            <br><br>
            <?= Html::a(Yii::t('rbac-admin', 'Assign') . '&gt;&gt;', ['assign'], [
                'class' => 'btn btn-primary btn-assign',
                'data-target' => 'avaliable',
                'title' => Yii::t('rbac-admin', 'Assign')
            ]) ?><br><br>
            <?= Html::a('&lt;&lt;' . Yii::t('rbac-admin', 'Remove'), ['remove'], [
                'class' => 'btn btn-primary btn-assign',
                'data-target' => 'assigned',
                'title' => Yii::t('rbac-admin', 'Remove')
            ]) ?>
        </div>
        <div class="col-sm-5">
            <input class="form-control search" data-target="assigned"
                   placeholder="<?= Yii::t('rbac-admin', 'Search for assigned') ?>">
            <select multiple size="20" class="form-control list" data-target="assigned"></select>
        </div>
    </div>
</section>
<script src="/rbac/js/_script.js"></script>