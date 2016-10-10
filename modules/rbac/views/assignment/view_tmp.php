<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use app\modules\rbac\AnimateAsset;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\rbac\models\Assignment */
/* @var $fullnameField string */

$userName = $model->{$usernameField};
if (!empty($fullnameField)) {
    $userName .= ' (' . ArrayHelper::getValue($model, $fullnameField) . ')';
}
$userName = Html::encode($userName);

$this->title = Yii::t('rbac-admin', 'Assignment') . ' : ' . $userName;

$this->params['breadcrumbs'][] = ['label' => Yii::t('rbac-admin', 'Assignments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $userName;

AnimateAsset::register($this);
YiiAsset::register($this);
$opts = Json::htmlEncode([
    'items' => $model->getItems()
]);
$this->registerJs("var _opts = {$opts};");
$this->registerJs($this->render('_script.js'));
$animateIcon = ' <i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>';
?>

<style>

    .dd-handle {
        height: 40px;
        line-height: 30px;
        width: 100px;
    }

    .field-item {
        -moz-user-select: none;
        border: 1px solid #ccc;
        border-radius: 3px;
        cursor: pointer;
        float: left;
        margin: 5px;
        padding: 10px;
        position: relative;
    }

    .field-item:active {
        background: #d9d9d9 none repeat scroll 0 0;
    }

    .drag {
        background: #d9d9d9 none repeat scroll 0 0;
    }

    .form-control .select2-choice {
        border: 0 none;
        border-radius: 2px;
        height: 32px;
        line-height: 32px;
    }

    .field-item.field-item-remove span {
        color: red;
        cursor: pointer;
        position: absolute;
        right: -5px;
        top: -10px;
    }
</style>
<section class="content">
    <div class="auth-item-view">
        <div class="row">
            <div class="col-sm-6">
                <input class="form-control search" data-target="avaliable" style="border-radius: 4px 4px 0px 0px"
                       placeholder="<?= Yii::t('rbac-admin', 'Search for avaliable') ?>">
                <div class="panel panel-default">
                    <div class="panel-heading" style="border-radius: 0px">未分配</div>
                    <input type="hidden" id="assign" name="assign" value="<?=\yii\helpers\Url::toRoute(['assign', 'id' => $model->id])?>">
                    <div id="new_fields" class="panel-body">
                    </div>
                </div>
            </div>
            <!--
            <div class="col-sm-1">
                <br><br>
                <?= Html::a(Yii::t('rbac-admin', 'Assign') . '&gt;&gt;', ['assign', 'id' => $model->id], [
                'class' => 'btn btn-primary btn-assign',
                'data-target' => 'avaliable',
                'title' => Yii::t('rbac-admin', 'Assign')
            ]) ?><br><br>
                <?= Html::a('&lt;&lt;' . Yii::t('rbac-admin', 'Remove'), ['remove', 'id' => $model->id], [
                'class' => 'btn btn-primary btn-assign',
                'data-target' => 'assigned',
                'title' => Yii::t('rbac-admin', 'Remove')
            ]) ?>

            </div>
             -->
            <div class="col-sm-6">
                <input class="form-control search" data-target="assigned" style="border-radius: 4px 4px 0px 0px"
                       placeholder="<?= Yii::t('rbac-admin', 'Search for assigned') ?>">
                <div class="panel panel-default">
                    <div class="panel-heading" style="border-radius: 0px">已分配</div>
                    <input type="hidden" id="remove" name="remove" value="<?=\yii\helpers\Url::toRoute(['remove', 'id' => $model->id])?>">
                    <div id="add_fields" class="panel-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

