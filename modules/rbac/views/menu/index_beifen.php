<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\widget;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

$this->title = Yii::t('rbac-admin', 'Menus');
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="/admin/plugins/iCheck/flat/blue.css">
<link rel="stylesheet" href="/api/bootstraptree/css/style.css">
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="tree well">
                    <ul style="padding-left: 0px">
                        <li>
                            <span><i class="fa fa-folder-open"></i> 菜单管理</span>
                            <?php if(!empty($levelmenulist)){?>
                            <ul>
                                <?php foreach($levelmenulist as $key => $item){?>
                                <li>
                                    <span><i class="fa fa fa-minus-circle"></i> <?=$item['name']?></span>
                                    <?php if(!empty($item['child'])){?>
                                    <ul>
                                        <?php foreach($item['child'] as $key => $item){ ?>
                                        <li>
                                            <span><?=!empty($item['child']) ? '<i class="fa fa-minus-circle"></i>':'<i class="fa fa-leaf">'?></i> <?=$item['name']?></span>
                                            <?php if(!empty($item['child'])){?>
                                                <ul>
                                                    <?php foreach($item['child'] as $key => $item){ ?>
                                                        <li>
                                                            <div class="btn-group">
                                                                <a href="#" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i><?=$item['name']?></a>
                                                                <a title="Reset Grid" href="/rbac/menu/grid-demo.html" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-repeat"></i>新增子菜单</a>
                                                                <a title="Reset Grid" href="/rbac/menu/grid-demo.html" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i>删除</a>
                                                            </div>
                                                        </li>
                                                    <?php }?>
                                                </ul>
                                            <?php }?>
                                        </li>
                                        <?php }?>
                                    </ul>
                                    <?php }?>
                                </li>
                                <?php }?>
                            </ul>
                            <?php }?>
                        </li>
                    </ul>
                </div>
            </div>
        </div><!-- /.col -->
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">节点列表</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'responsive'=>true,//自适应，默认为true
                        'hover'=>false,//鼠标移动上去时，颜色变色，默认为false
                        //'showPageSummary' => true,//显示总计
                        'columns' => [
                            //全选复选框设置
                            [
                                'class' => '\kartik\grid\CheckboxColumn',
                                'rowSelectedClass' => GridView::TYPE_INFO,
                                'visible'=>true,//不显示，代码也没有
                                'headerOptions'=>['width'=>'80'],
                                'hidden'=>false,//隐藏，代码还有，导出csv等时还存在
                                'hiddenFromExport'=>true,//虽然显示，但导出csv时忽略掉
                                'pageSummary'=>'总计',//可以是字符串，当为true时，自动合计
                                'mergeHeader'=>true,//合并标题和检索栏
                            ],
                            'name',
                            [
                                'attribute' => 'menuParent.name',
                                'filter' => Html::activeTextInput($searchModel, 'parent_name', [
                                    'class' => 'form-control', 'id' => null
                                ]),
                                'label' => Yii::t('rbac-admin', 'Parent'),
                            ],
                            'route',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header'=>'操作',
                                'headerOptions'=>['width'=>'200'],
                                'template' => '{view} {update} {delete}',
                                'buttons'=>[
                                    'view' => function($url,$model){
                                        return Html::a('<i class="fa fa-eye"></i>查看',['/rbac/menu/view','id'=>$model->id], ['class' => 'btn btn-primary btn-sm']);
                                    },
                                    'update' => function($url,$model){
                                        return Html::a('<i class="fa fa-pencil"></i>编辑',['/rbac/menu/update','id'=>$model->id], ['class' => 'btn btn-primary btn-sm']);
                                    },
                                    'delete'=>function($url,$model){
                                        return Html::a('<i class="fa fa-trash-o"></i>删除',['/rbac/menu/delete','id'=>$model->id], ['class' => 'btn btn-primary btn-sm']);
                                    }
                                ],
                            ],
                        ],
                        'toolbar' => [
                            [
                                'content'=>
                                    Html::a('<i class="fa fa-plus-circle"></i>'.Yii::t('rbac-admin', 'Create Menu'), ['create'], [
                                        'class' => 'btn btn-primary btn-sm'
                                    ]) . ' '.
                                    Html::a('<i class="glyphicon glyphicon-repeat"></i>刷新', ['grid-demo'], [
                                        'class' => 'btn btn-default btn-sm',
                                        'title' => 'Reset Grid'
                                    ]). ' '.
                                    Html::a('<i class="fa fa-trash-o"></i>批量删除', ['grid-demo'], [
                                        'class' => 'btn btn-default btn-sm',
                                        'title' => 'Reset Grid'
                                    ]),
                            ],
                        ],
                        'panel' => [
                            'heading'=>false,//不要了
                            'before'=>'<div style="margin-top:8px">{summary}</div>',//放在before中，前面的div主要是想让它好看
                        ],
                        'pager' => [
                            'firstPageLabel' => '首页',
                            'lastPageLabel' => '末页',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel' => '下一页',
                            'maxButtonCount' => 5
                        ],
                    ]); ?>
                </div><!-- /.box-header -->
            </div><!-- /. box -->
        </div><!-- /.col -->
    </div><!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="tree well">
                    <ul style="padding-left: 0px">
                        <li>
                            <span><i class="fa fa-folder-open"></i> 菜单管理</span>
                            <?php if(!empty($levelmenulist)){?>
                                <ul>
                                    <?php foreach($levelmenulist as $key => $item){?>
                                        <li>
                                            <span><i class="fa fa fa-minus-circle"></i> <?=$item['name']?></span>
                                            <?php if(!empty($item['child'])){?>
                                                <ul>
                                                    <?php foreach($item['child'] as $key => $item){ ?>
                                                        <li>
                                                            <span><?=!empty($item['child']) ? '<i class="fa fa-minus-circle"></i>':'<i class="fa fa-leaf">'?></i> <?=$item['name']?></span>
                                                            <?php if(!empty($item['child'])){?>
                                                                <ul>
                                                                    <?php foreach($item['child'] as $key => $item){ ?>
                                                                        <li>
                                                                            <div class="btn-group">
                                                                                <a href="#" class="btn btn-default btn-sm"><i class="fa fa-plus-circle"></i><?=$item['name']?></a>
                                                                                <a title="Reset Grid" href="/rbac/menu/grid-demo.html" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-repeat"></i>新增子菜单</a>
                                                                                <a title="Reset Grid" href="/rbac/menu/grid-demo.html" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i>删除</a>
                                                                            </div>
                                                                        </li>
                                                                    <?php }?>
                                                                </ul>
                                                            <?php }?>
                                                        </li>
                                                    <?php }?>
                                                </ul>
                                            <?php }?>
                                        </li>
                                    <?php }?>
                                </ul>
                            <?php }?>
                        </li>
                    </ul>
                </div>
            </div>
        </div><!-- /.col -->
        <div class="col-md-8">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">节点列表</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'responsive'=>true,//自适应，默认为true
                        'hover'=>false,//鼠标移动上去时，颜色变色，默认为false
                        //'showPageSummary' => true,//显示总计
                        'columns' => [
                            //全选复选框设置
                            [
                                'class' => '\kartik\grid\CheckboxColumn',
                                'rowSelectedClass' => GridView::TYPE_INFO,
                                'visible'=>true,//不显示，代码也没有
                                'headerOptions'=>['width'=>'80'],
                                'hidden'=>false,//隐藏，代码还有，导出csv等时还存在
                                'hiddenFromExport'=>true,//虽然显示，但导出csv时忽略掉
                                'pageSummary'=>'总计',//可以是字符串，当为true时，自动合计
                                'mergeHeader'=>true,//合并标题和检索栏
                            ],
                            'name',
                            [
                                'attribute' => 'menuParent.name',
                                'filter' => Html::activeTextInput($searchModel, 'parent_name', [
                                    'class' => 'form-control', 'id' => null
                                ]),
                                'label' => Yii::t('rbac-admin', 'Parent'),
                            ],
                            'route',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header'=>'操作',
                                'headerOptions'=>['width'=>'200'],
                                'template' => '{view} {update} {delete}',
                                'buttons'=>[
                                    'view' => function($url,$model){
                                        return Html::a('<i class="fa fa-eye"></i>查看',['/rbac/menu/view','id'=>$model->id], ['class' => 'btn btn-primary btn-sm']);
                                    },
                                    'update' => function($url,$model){
                                        return Html::a('<i class="fa fa-pencil"></i>编辑',['/rbac/menu/update','id'=>$model->id], ['class' => 'btn btn-primary btn-sm']);
                                    },
                                    'delete'=>function($url,$model){
                                        return Html::a('<i class="fa fa-trash-o"></i>删除',['/rbac/menu/delete','id'=>$model->id], ['class' => 'btn btn-primary btn-sm']);
                                    }
                                ],
                            ],
                        ],
                        'toolbar' => [
                            [
                                'content'=>
                                    Html::a('<i class="fa fa-plus-circle"></i>'.Yii::t('rbac-admin', 'Create Menu'), ['create'], [
                                        'class' => 'btn btn-primary btn-sm'
                                    ]) . ' '.
                                    Html::a('<i class="glyphicon glyphicon-repeat"></i>刷新', ['grid-demo'], [
                                        'class' => 'btn btn-default btn-sm',
                                        'title' => 'Reset Grid'
                                    ]). ' '.
                                    Html::a('<i class="fa fa-trash-o"></i>批量删除', ['grid-demo'], [
                                        'class' => 'btn btn-default btn-sm',
                                        'title' => 'Reset Grid'
                                    ]),
                            ],
                        ],
                        'panel' => [
                            'heading'=>false,//不要了
                            'before'=>'<div style="margin-top:8px">{summary}</div>',//放在before中，前面的div主要是想让它好看
                        ],
                        'pager' => [
                            'firstPageLabel' => '首页',
                            'lastPageLabel' => '末页',
                            'prevPageLabel' => '上一页',
                            'nextPageLabel' => '下一页',
                            'maxButtonCount' => 5
                        ],
                    ]); ?>
                </div><!-- /.box-header -->
            </div><!-- /. box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
<script src="/admin/plugins/iCheck/icheck.min.js"></script>
<!-- Page Script -->
<script>
    $(function () {
        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        $('.mailbox-messages input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });
        //Enable check and uncheck all functionality
        $("#listview").on('click', '.checkbox-toggle', function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                //Uncheck all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                //Check all checkboxes
                $(".mailbox-messages input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });

        //Handle starring for glyphicon and font awesome
        $("#listview").on('click', '.mailbox-star', function (e) {
            e.preventDefault();
            //detect type
            var $this = $(this).find("a > i");
            var glyph = $this.hasClass("glyphicon");
            var fa = $this.hasClass("fa");

            //Switch states
            if (glyph) {
                $this.toggleClass("glyphicon-star");
                $this.toggleClass("glyphicon-star-empty");
            }

            if (fa) {
                $this.toggleClass("fa-star");
                $this.toggleClass("fa-star-o");
            }
        });
    });
    $(function () {
        $('.tree li:has(ul)').addClass('parent_li').find(' > span').attr('title', 'Collapse this branch');
        $('.tree li.parent_li > span').on('click', function (e) {
            var children = $(this).parent('li.parent_li').find(' > ul > li');
            if (children.is(":visible")) {
                children.hide('fast');
                $(this).attr('title', 'Expand this branch').find(' > i').addClass('fa-plus-circle').removeClass('fa-minus-circle');
            } else {
                children.show('fast');
                $(this).attr('title', 'Collapse this branch').find(' > i').addClass('fa-minus-circle').removeClass('fa-plus-circle');
            }
            e.stopPropagation();
        });
    });
    $('#listview').on('click', '.pagination a', function () {
        $.ajax({
            url: $(this).attr('href'),
            success: function (html) {
                $('#listview').html(html);
            }
        });
        return false;//阻止a标签
    });

</script>
