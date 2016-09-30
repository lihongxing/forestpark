<?php
use xiaohei\widgetform\FormWidget;
?>
<!DOCTYPE html>
<html class="no-focus">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
<title>表单组件测试</title>
<meta name="description" content=""/>
<link rel="shortcut icon" href="http://localhost/attachment/images/global/wechat.jpg" />
<link href="/resource/css/bootstrap.min.css" rel="stylesheet">
<link href="/resource/css/font-awesome.min.css" rel="stylesheet">
<link href="/resource/css/common.css" rel="stylesheet">
<script>var require = { urlArgs: 'v=2016040114' };</script>
<script src="/resource/js/lib/jquery-1.11.1.min.js"></script>
<script src="/resource/js/app/util.js"></script>
<script src="/resource/js/require.js"></script>
<script src="/resource/js/app/config.js"></script>
</head>
<body>
<?= FormWidget::widget(['name' => 'thumb', 'value' => '', 'default' => '', 'options' => array('width' => 400, 'extras' => array('text' => 'ng-model="entry.thumb"'))]) ?>

<?= FormWidget::widget(['name' => 'thumbs', 'value' => '']) ?>

<?= FormWidget::widget(['name' => 'time', 'value' => array('starttime'=>date('Y-m-d H:i', $starttime),'endtime'=>date('Y-m-d  H:i', $endtime)),'options' => true])?>

<?= FormWidget::widget(['name' => 'timestart', 'value' => !empty($item['timestart']) ? date('Y-m-d H:i',$item['timestart']) : date('Y-m-d H:i'),'options' => true])?>

<?= FormWidget::widget(['name' => 'content', 'value' => ''])?>

<?= FormWidget::widget(['name' => 'musicurl', 'value' => '', 'options' => array('')])?>
<input class="fe-panel-editor-input2" type="color" ng-model="Edit.params.bgcolor" />
<script src="/resource/color/js/st.js"></script>
<script src="/resource/color/js/commonp.js"></script>
</body>
</html>