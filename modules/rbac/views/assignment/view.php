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


<section class="content">
    <div class="auth-item-view">
        <div class="row">
            <div class="col-sm-5">
                <input class="form-control search" data-target="avaliable"
                       placeholder="<?= Yii::t('rbac-admin', 'Search for avaliable') ?>">
                <select multiple size="20" class="form-control list" data-target="avaliable">
                </select>
            </div>
            <div class="col-sm-1">
                <br><br>
                <?= Html::a( Yii::t('rbac-admin', 'Assign').'&gt;&gt;' , ['assign', 'id' => $model->id], [
                    'class' => 'btn btn-primary btn-assign',
                    'data-target' => 'avaliable',
                    'title' => Yii::t('rbac-admin', 'Assign')
                ]) ?><br><br>
                <?= Html::a('&lt;&lt;' .  Yii::t('rbac-admin', 'Remove'), ['remove', 'id' => $model->id], [
                    'class' => 'btn btn-primary btn-assign',
                    'data-target' => 'assigned',
                    'title' => Yii::t('rbac-admin', 'Remove')
                ]) ?>
            </div>
            <div class="col-sm-5">
                <input class="form-control search" data-target="assigned"
                       placeholder="<?= Yii::t('rbac-admin', 'Search for assigned') ?>">
                <select multiple size="20" class="form-control list" data-target="assigned">
                </select>
            </div>
        </div>
    </div>
</section>
