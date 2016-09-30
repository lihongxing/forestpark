<?php
use yii\grid\GridView;
use yii\widgets\LinkPager;
?>


<!DOCTYPE html>
<html class="no-focus">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">
<title>带分页大小的分页测试</title>
<meta name="description" content=""/>
</head>
<body>
<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns' => [
    ],
    'filterSelector' => "select[name='".$dataProvider->getPagination()->pageSizeParam."'],input[name='".$dataProvider->getPagination()->pageParam."']",
    'summary'=>false,//关闭顶部总条数
    'pager' => [
        'class' => \liyunfang\pager\LinkPager::className(),
        'template' => '{pageButtons} {customPage} {pageSize}', //分页栏布局
        'pageSizeList' => [5, 10, 15,20], //页大小下拉框值
        'customPageWidth' => 50,            //自定义跳转文本框宽度
        'customPageBefore' => ' 跳转到第 ',
        'customPageAfter' => ' 页 ',
        'firstPageLabel'=>"首页",
        'prevPageLabel'=>'上一页',
        'nextPageLabel'=>'下一页',
        'lastPageLabel'=>'尾页',
    ],
]) ?>
</body>
</html>