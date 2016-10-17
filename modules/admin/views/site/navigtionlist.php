<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = Yii::t('admin', 'navigtionmanage');
?>
<link href="/api/bootstrapswitch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
<script src="/api/bootstrapswitch/dist/js/bootstrap-switch.min.js"></script>
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i><?=Yii::t('admin', 'sitemanage');?></a></li>
        <li><a href="#"><?=$this->title?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=$this->title?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="panel-body">
                        <form id="form1" role="form" class="form-horizontal" method="get" action="<?=Url::toRoute('/admin/site/navigtion-list')?>">
                            <div class="form-group">
                                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">导航信息</label>
                                <div class="col-sm-8 col-lg-9 col-xs-12">
                                    <input type="text" placeholder="可搜索导航名称,标题" value="<?=$GET['keyword']?>" name="keyword" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
                                <div class="col-sm-7 col-lg-9 col-xs-12">
                                    <button class="btn btn-default" data-original-title="" title=""><i class="fa fa-search"></i> 搜索</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 40px">
                                <button style="padding:1px 4px" id="checkboxall"
                                        class="btn btn-default btn-sm checkbox-toggle"><i
                                        class="fa fa-square-o"></i>
                                </button>
                            </th>
                            <th>导航名称</th>
                            <th>导航链接</th>
                            <th>导航标题</th>
                            <th>排序</th>
                            <th>状态</th>
                            <th style="width: 140px">操作</th>
                        </tr>
                        <?php if (!empty($navigations)) { ?>
                            <?php foreach ($navigations as $key => $item) { ?>
                                <tr class="odd gradeX">
                                    <td><input type="checkbox" name="checkbox" value="<?= $item['nav_id'] ?>" data-size="small" class="checkboxes"></td>
                                    <td><?= $item['nav_name'] ?></td>
                                    <td><?= $item['nav_href'] ?></td>
                                    <td><?= $item['nav_title'] ?></td>
                                    <td><?= $item['nav_order'] ?></td>
                                    <td><input value="<?= $item['nav_id']?>" id="switch" name="status" <?= $item['nav_show']==1 ? "checked": ""?>   type="checkbox"  data-size="small" data-on-text="显示" data-off-text="隐藏"></td>
                                    <td>
                                        <a href="<?=Url::toRoute(["/admin/site/navigtion-form",'nav_id'=> $item['nav_id']])?>"  class="btn btn-primary btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-pencil"></i>编辑
                                        </a>
                                        <a href="#" onclick="deletebyid(<?= $item['nav_id']?>);" class="btn btn-primary btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-trash-o"></i>删除
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr class="odd gradeX">
                                <td style ="text-align: center" colspan="7">当前未添加任何导航！</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <div class="btn-group">
                        <a style="width: 80px" class="btn btn-primary"
                           href="#" id="delselect"> 删除选中</a>
                        <a class="btn btn-default" href="#" id="delall"> 删除全部</a>
                        <a class="btn btn-default" href="<?=Url::toRoute('/admin/site/navigtion-form')?>"><i
                                class="fa fa-fw fa-plus-square"></i> 新增导航</a>
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
        </div><!-- /.row -->
    </div>
</section>
<script type="text/javascript">
    $(function () {
        $('[name="status"]').bootstrapSwitch();
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
    });
    function deletebyid(nav_id) {
        dialog({
            title: prompttitle,
            content: '你确定要删除导航吗？',
            okValue: '确定',
            ok: function () {
                this.title('提交中…');
                $.ajax({
                    type: "POST",
                    url: '<?=Url::toRoute("/admin/site/nav-delete")?>',
                    //提交的数据
                    data: {nav_id: nav_id, type: 1, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                    datatype: "json",
                    success: function (data) {
                        data = eval("(" + data + ")");
                        switch(data.status){
                            case 100:
                                content = '导航删除成功';
                                break;
                            case 101:
                            case 102:
                            case 103:
                                content = '导航删除失败';
                                break;
                            case 403:
                                content = '你没有删除导航菜单';
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
    $('input[name="status"]').on('switchChange.bootstrapSwitch', function(event, state) {
        nav_id = $(this).val();
        $.ajax({
            type: "POST",
            url: '<?=Url::toRoute("/admin/site/nav-status")?>',
            data: {nav_id: nav_id, status: state, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
            datatype: "json",
            success: function (data) {
                data = eval("(" + data + ")");
                if(data.status == 403){
                    content = '你没有设置导航菜单显示隐藏的权限';
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

    $("#delselect").click(function () {
        require(["dialog"], function (dialog) {
            //获取选中需要备份的表的表名称
            var chk_value = [];
            $(".checked").each(function () {
                chk_value.push($(this).children().val());
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
                    content: '你确定要删除选中的导航吗？',
                    okValue: '确定',
                    ok: function () {
                        this.title('提交中…');
                        $.ajax({
                            type: "POST",
                            url: '<?=Url::toRoute("/admin/site/nav-delete")?>',
                            data: {nav_ids: chk_value, type: 2, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                            datatype: "json",
                            success: function (data) {
                                data = eval("(" + data + ")");
                                switch(data.status){
                                    case 100:
                                        content = '导航删除成功';
                                        break;
                                    case 101:
                                    case 102:
                                    case 103:
                                        content = '导航删除失败';
                                        break;
                                    case 403:
                                        content = '你没有删除导航菜单的权限';
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
                content: '确定删除全部导航吗？',
                okValue: '确定',
                ok: function () {
                    this.title('提交中…');
                    $.ajax({
                        type: "POST",
                        url: '<?=Url::toRoute("/admin/site/nav-delete")?>',
                        data: {type: 3, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                        datatype: "json",
                        success: function (data) {
                            data = eval("(" + data + ")");
                            switch(data.status){
                                case 100:
                                    content = '导航删除成功';
                                    break;
                                case 101:
                                case 102:
                                case 103:
                                    content = '导航删除失败';
                                    break;
                                case 403:
                                    content = '你没有删除导航菜单的权限';
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
</script>
