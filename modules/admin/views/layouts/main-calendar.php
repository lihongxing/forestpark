<?php
use yii\helpers\Html;

if (Yii::$app->controller->action->id === 'login') {
    /**
     * 登陆不使用布局配置
     */
    echo $this->render(
        '/user/login',
        ['content' => $content]
    );
} else {
    dmstr\web\AdminLteAsset::register($this);
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/AdminLTE/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?=Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <script src="/resource/js/lib/jquery-1.11.1.min.js"></script>
        <link rel="stylesheet" href="/admin/plugins/select2/select2.min.css">
        <link rel="stylesheet" href="/admin/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="/admin/bootstrap/css/bootstrap.min.css">
        <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
        <link href="/resource/css/common.css" rel="stylesheet">
        <link rel="shortcut icon" href="<?=yii::$app->params['siteinfo']['siteurl'].'/'.yii::$app->params['siteinfo']['icon']?>">
        <link rel="stylesheet" href="/api/artDialog/css/ui-dialog.css">
        <script src="/api/artDialog/dist/dialog-min.js"></script>
        <script src="/admin/js/language-ch_zn.js"></script>
        <script src="/admin/plugins/iCheck/icheck.min.js"></script>
        <link rel="stylesheet" href="/admin/plugins/iCheck/flat/blue.css">
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <?= \app\modules\admin\widgets\Messages::widget() ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>
        <?= $this->render(
            'foot.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
