<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="content-wrapper">
    <section class="content">
        <section class="content-header">
            <?=
            Breadcrumbs::widget([
                'encodeLabels' => false,
                'homeLink'=>['label' => "<i class='fa fa-dashboard'></i>首页", 'url' => Yii::$app->homeUrl],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
