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
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="tree well">
                    <ul style="padding-left: 0px">
                        <li>
                            <span><i class="fa fa-folder-open"></i> 菜单管理</span>
                            <?php if(!empty($levelmenulist)){?>
                            <ul>
                                <?php foreach($levelmenulist as $key => $item){?>
                                <li>
                                    <span>
                                        <i class="fa fa fa-minus-circle"></i>
                                        <?=$item['name']?>
                                        <d style="display:none" ><?=$item['id']?></d>
                                    </span>
                                    <?php if(!empty($item['child'])){?>
                                    <ul>
                                        <?php foreach($item['child'] as $key => $item){ ?>
                                        <li>
                                            <span>
                                                <?=!empty($item['child']) ? '<i class="fa fa-minus-circle"></i>':'<i class="fa fa-leaf">'?></i>
                                                <?=$item['name']?>
                                                <d style="display:none" ><?=$item['id']?></d>
                                            </span>
                                            <?php if(!empty($item['child'])){?>
                                                <ul>
                                                    <?php foreach($item['child'] as $key => $item){ ?>
                                                        <li>
                                                            <span>
                                                                <i class="fa fa-leaf"></i>
                                                                <?=$item['name']?>
                                                                <d style="display:none" ><?=$item['id']?></d>
                                                            </span>
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
        <div class="col-md-9">
            <div class="panel panel-info">
                <div class="panel-heading">筛选</div>
                <div class="panel-body">
                    <form role="form" class="form-horizontal" method="get" action="<?=Url::toRoute('/rbac/menu/index')?>">
                        <input type="hidden" value="-1" name="status">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-4 col-lg-2 control-label">状态</label>
                            <div class="col-sm-8 col-lg-9 col-xs-12">
                                <div class="btn-group">
                                    <a class="btn <?php if($status == -1){?>btn-primary <?php }else{?>btn-default<?php }?>" href="<?=Url::toRoute(['/rbac/menu/index','status' => -1])?>">所有</a>
                                    <a class="btn <?php if($status == 1){?>btn-primary <?php }else{?>btn-default<?php }?>" href="<?=Url::toRoute(['/rbac/menu/index','status' => 1])?>">显示</a>
                                    <a class="btn <?php if($status == 0){?>btn-primary <?php }else{?>btn-default<?php }?>" href="<?=Url::toRoute(['/rbac/menu/index','status' => 0])?>">隐藏</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-4 col-lg-2 control-label">菜单名称</label>
                            <div class="col-sm-8 col-xs-12">
                                <input type="text" value="" id="name" name="name" class="form-control">
                            </div>
                            <div class="col-xs-12 col-sm-3 col-lg-1 text-right">
                                <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=$this->title;?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 40px">
                                <button style="padding:1px 4px" id="checkboxall"
                                        class="btn btn-default btn-sm checkbox-toggle"><i
                                        class="fa fa-square-o"></i>
                                </button>
                            </th>
                            <th>菜单名称</th>
                            <th>父级菜单</th>
                            <th>路由</th>
                            <th>状态</th>
                            <th>图标</th>
                            <th style="width: 220px">操作</th>
                        </tr>
                        <?php if (!empty($memus)) { ?>
                            <?php foreach ($memus as $key => $item) { ?>
                                <tr class="odd gradeX">
                                    <td><input type="checkbox" name="checkbox" data-size="small" class="checkboxes"></td>
                                    <input type="hidden" name="menuid" value="<?= $item['id'] ?>">
                                    <td><?= $item['name'] ?></td>
                                    <td><?= $item['parent'] ?></td>
                                    <td><?= $item['route'] ?></td>
                                    <td><input value="<?= $item['id']?>" id="switch" name="menustatus" <?= $item['status']==1 ? "checked": ""?>   type="checkbox"  data-size="small" data-on-text="显示" data-off-text="隐藏"></td>
                                    <td><i class="<?=json_decode($item['data'])->icon?>"></i></td>
                                    <td>
                                        <button onclick="viewinfo();"
                                                class="btn btn-info btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa fa-eye"></i> 查看
                                        </button>
                                        <a href="<?=Url::toRoute(["/rbac/menu/update",'id'=> $item['id']])?>"  class="btn btn-primary btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-pencil"></i>编辑
                                        </a>
                                        <a href="#" onclick="deletebyid(<?= $item['id']?>);" class="btn btn-danger btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-trash-o"></i>删除
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr class="odd gradeX">
                                <td style ="text-align: center" colspan="7">当前未添加任何用户！</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <div class="btn-group">
                        <a class="btn btn-primary"
                           href="#" id="delselect"> 删除选中自定义菜单</a>
                        <a class="btn btn-default" href="#" id="delall"> 删除全部自定义菜单</a>
                        <a class="btn btn-default" href="<?=Url::toRoute('/rbac/menu/create')?>"><i
                                class="fa fa-fw fa-plus-square"></i> 新增菜单</a>
                    </div>
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?php
                        echo LinkPager::widget([
                                'pagination' => $pages,
                                'firstPageLabel' => '首页',
                                'lastPageLabel' => '末页',
                                'prevPageLabel' => '上一页',
                                'nextPageLabel' => '下一页',
                                'maxButtonCount' => 5
                            ]
                        );
                        ?>
                    </ul>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
<script src="/admin/plugins/iCheck/icheck.min.js"></script>
<link href="/api/bootstrapswitch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
<script src="/api/bootstrapswitch/dist/js/bootstrap-switch.min.js"></script>
<!-- Page Script -->
<script>
    $(function () {
        $(".tree span").click(function(){
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
    $('[name="menustatus"]').bootstrapSwitch();
    $('.box-body input[name="checkbox"]').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
    $('#checkboxall').click(function () {
        var clicks = $(this).data('clicks');
        if (clicks) {
            $('.box-body input[name="checkbox"]').iCheck("uncheck");
            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
        } else {
            $('.box-body input[name="checkbox"]').iCheck("check");
            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
        }
        $(this).data("clicks", !clicks);
    });
    function deletebyid(id) {
        dialog({
            title: prompttitle,
            content: authrbac.menudel,
            okValue: '确定',
            ok: function () {
                this.title('提交中…');
                $.ajax({
                    type: "POST",
                    url: '<?=Url::toRoute("/rbac/menu/delete")?>',
                    //提交的数据
                    data: {id: id, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                    datatype: "json",
                    success: function (data) {
                        data = eval("(" + data + ")");
                        switch(data.status){
                            case 403:
                                content = authrbac.rbacerror
                                break;
                            case 100:
                                content = authrbac.menudel100;
                                break;
                            case 101:
                            case 102:
                                content = authrbac.menudel101;
                                break;
                            case 103:
                                content = authrbac.menudel103;
                                break;
                        }
                        content =
                            dialog({
                                title: prompttitle,
                                content: content,
                                cancel: false,
                                okValue: '确定',
                                ok: function () {
                                    window.location.reload();
                                }
                            }).showModal();
                    }
                });
            },
            cancelValue: '取消',
            cancel: function () {
            }
        }).showModal();
    }


    $("#delselect").click(function () {
        require(["dialog"], function (dialog) {
            //获取选中需要备份的表的表名称
            var chk_value = [];
            $(".checked").each(function () {
                chk_value.push($(this).parent().next().val());
            });
            if (chk_value.length == 0) {
                dialog({
                    title: prompttitle,
                    content: checklength0,
                    cancel: false,
                    okValue: '确定',
                    ok: function () {
                    }
                }).showModal();
            } else {
                dialog({
                    title: prompttitle,
                    content: authrbac.menudel,
                    okValue: '确定',
                    ok: function () {
                        this.title('提交中…');
                        $.ajax({
                            type: "POST",
                            url: '<?=Url::toRoute("/rbac/menu/deletesel")?>',
                            data: {ids: chk_value, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                            datatype: "json",
                            success: function (data) {
                                data = eval("(" + data + ")");
                                switch(data.status){
                                    case 403:
                                        content = authrbac.rbacerror
                                        break;
                                    case 100:
                                        content = authrbac.menudel100;
                                        break;
                                    case 101:
                                    case 102:
                                        content = authrbac.menudel101;
                                        break;
                                    case 103:
                                        content = authrbac.menudel103;
                                        break;
                                }
                                dialog({
                                    title: prompttitle,
                                    content: content,
                                    cancel: false,
                                    okValue: '确定',
                                    ok: function () {
                                        window.location.reload();
                                    }
                                }).showModal();
                            }
                        });
                    },
                    cancelValue: '取消',
                    cancel: function () {
                    }
                }).showModal();
            }
        });
    });


    $("#delall").click(function () {
        require(["dialog"], function (dialog) {
            dialog({
                title: prompttitle,
                content: authrbac.menudel,
                okValue: '确定',
                ok: function () {
                    this.title('提交中…');
                    $.ajax({
                        type: "POST",
                        url: '<?=Url::toRoute("/rbac/menu/deleteall")?>',
                        data: {_csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                        datatype: "json",
                        success: function (data) {
                            data = eval("(" + data + ")");
                            switch(data.status){
                                case 403:
                                    content = authrbac.rbacerror
                                    break;
                                case 100:
                                    content = authrbac.menudel100;
                                    break;
                                case 101:
                                    content = authrbac.menudel101;
                                    break;
                            }
                            dialog({
                                title: prompttitle,
                                content: content,
                                cancel: false,
                                okValue: '确定',
                                ok: function () {
                                    window.location.reload();
                                }
                            }).showModal();
                        }
                    });
                },
                cancelValue: '取消',
                cancel: function () {
                }
            }).showModal();
        });
    });


    $('input[name="menustatus"]').on('switchChange.bootstrapSwitch', function(event, state) {
        id = $(this).val();
        $.ajax({
            type: "POST",
            url: '<?=Url::toRoute("/rbac/menu/status")?>',
            data: {id: id, status: state, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
            datatype: "json",
            success: function (data) {
                data = eval("(" + data + ")");
                if(data.status == 403){
                    content = data.message;
                }else if(data.status == 104){
                    content = '系统菜单无法隐藏'
                }
                dialog({
                    title: prompttitle,
                    content: content,
                    cancel: false,
                    okValue: '确定',
                    ok: function () {
                        window.location.reload();
                    }
                }).showModal();
            }
        });
    });

</script>
