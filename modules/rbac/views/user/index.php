<?php

use yii\helpers\Url;
use yii\widgets\LinkPager;
$this->title = Yii::t('rbac-admin', 'Users');
?>
<link href="/api/bootstrapswitch/dist/css/bootstrap3/bootstrap-switch.css" rel="stylesheet">
<script src="/api/bootstrapswitch/dist/js/bootstrap-switch.min.js"></script>
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i><?=Yii::t('rbac-admin', 'Rbac Manage');?></a></li>
        <li><a href="#"><?=Yii::t('rbac-admin', 'User Manage');?></a></li>
        <li><a href="#"><?=Yii::t('rbac-admin', 'Users');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=Yii::t('rbac-admin', 'Users');?></h3>
                </div>
                <div class="panel-body">
                    <form id="form1" role="form" class="form-horizontal" method="get" action="<?=Url::toRoute('/rbac/user/index')?>">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">用户信息</label>
                            <div class="col-sm-8 col-lg-9 col-xs-12">
                                <input type="text" placeholder="可搜索用户名/手机号/邮箱" value="<?=$GET['name']?>" name="name" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">启用禁用</label>
                            <div class="col-sm-8 col-lg-9 col-xs-12">
                                <select class="form-control" name="followed">
                                    <option value=""></option>
                                    <option value="10" <?php if($GET['followed'] == 10){?> selected <?php }?> >启用</option>
                                    <option value="1" <?php if($GET['followed'] == 1){?> selected <?php }?>>禁用</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">用户分组</label>
                            <div class="col-sm-8 col-lg-9 col-xs-12">
                                <select class="form-control" name="groupid">
                                    <option value=""></option>
                                    <?php if(!empty($ids)){?>
                                        <?php foreach($ids as $key => $item){?>
                                            <option value=<?=$item['name']?> <?php if($GET['groupid'] == $item['name']){?> selected <?php }?>><?=$item['name']?></option>
                                        <?php }?>
                                    <?php }?>
                                </select>
                            </div>

                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">添加时间</label>
                            <div class="col-sm-2">
                                <label class="radio-inline">
                                    <input type="radio" name="searchtime"  <?php if($GET['searchtime'] == 0){?> checked <?php }?> value="0">不搜索
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="searchtime" <?php if($GET['searchtime'] == 1){?> checked <?php }?> value="1">搜索
                                </label>
                            </div>
                            <div class="col-sm-7 col-lg-9 col-xs-12">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'time', 'value' => array('starttime'=>date('Y-m-d H:i', time()),'endtime'=>date('Y-m-d  H:i', time())), 'default' => false ,'options' => array()]) ?>
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
                            <th  style="width: 50px">编号</th>
                            <th>用户名</th>
                            <th>邮箱</th>
                            <th style="width: 120px">手机号码</th>
                            <th style="width: 120px">状态</th>
                            <th>创建时间</th>
                            <th style="width: 140px">操作</th>
                        </tr>
                        <?php if (!empty($users)) { ?>
                            <?php foreach ($users as $key => $item) { ?>
                                <tr class="odd gradeX">
                                    <td><input type="checkbox" name="usercheckbox" data-size="small" class="checkboxes"></td>
                                    <td><?= $key+1?></td>
                                    <td><?= $item['username'] ?></td>
                                    <td><?= $item['email'] ?></td>
                                    <td><?= $item['mobile'] ?></td>
                                    <td><input value="<?= $item['id']?>" id="switch" name="userstatus" <?= $item['status']==1 ? "checked": ""?>   type="checkbox"  data-size="small" data-on-text="启用" data-off-text="禁用"></td>
                                    <td><?= date('Y年m月d日 H时m分s秒',$item['created_at']) ?></td>
                                    <td>
                                        <a href="<?=Url::toRoute(["/rbac/user/view",'id'=> $item['id']])?>"  class="btn btn-primary btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-pencil"></i>编辑
                                        </a>
                                        <a href="#" onclick="deletebyid(<?= $item['id']?>);" class="btn btn-primary btn-sm checkbox-toggle" type="button">
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
                        <a style="width: 80px" class="btn btn-primary"
                           href="#" id="delselect"> 删除选中</a>
                        <a class="btn btn-default" href="#" id="delall"> 删除全部</a>
                        <a class="btn btn-default" href="<?=Url::toRoute('/rbac/user/signup')?>"><i
                                class="fa fa-fw fa-plus-square"></i> 新增用户</a>
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
        $('[name="userstatus"]').bootstrapSwitch();
        $('.box-body input[name="usercheckbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });
        $('#checkboxall').click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                $('.box-body input[name="usercheckbox"]').iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                $('.box-body input[name="usercheckbox"]').iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });
    });
    function deletebyid(id) {
        dialog({
            title: prompttitle,
            content: authrbac.userdel,
            okValue: '确定',
            ok: function () {
                this.title('提交中…');
                $.ajax({
                    type: "POST",
                    url: '<?=Url::toRoute("/rbac/user/delete")?>',
                    //提交的数据
                    data: {id: id, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                    datatype: "json",
                    success: function (data) {
                        data = eval("(" + data + ")");
                        switch(data.status){
                            case 100:
                                content = authrbac.userdel100;
                                break;
                            case 101:
                            case 102:
                                content = authrbac.userdel101;
                                break;
                            case 103:
                                content = authrbac.userdel103;
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
    $('input[name="userstatus"]').on('switchChange.bootstrapSwitch', function(event, state) {
        id = $(this).val();
        $.ajax({
            type: "POST",
            url: '<?=Url::toRoute("/rbac/user/status")?>',
            data: {id: id, status: state, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
            datatype: "json",
            success: function (data) {
                data = eval("(" + data + ")");
                if(data.status == 403){
                    content = data.message;
                }else if(data.status == 104){
                    content = '超级管理员无法禁用启用'
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
                chk_value.push($(this).parent().next().text());
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
                    content: authrbac.userdel,
                    okValue: '确定',
                    ok: function () {
                        this.title('提交中…');
                        $.ajax({
                            type: "POST",
                            url: '<?=Url::toRoute("/rbac/user/deleteselect")?>',
                            data: {ids: chk_value, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                            datatype: "json",
                            success: function (data) {
                                data = eval("(" + data + ")");
                                switch(data.status){
                                    case 403:
                                        content = authrbac.rbacerror
                                        break;
                                    case 100:
                                        content = authrbac.userdel100;
                                        break;
                                    case 101:
                                    case 102:
                                        content = authrbac.userdel101;
                                        break;
                                    case 103:
                                        content = authrbac.userdel103;
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
                content: authrbac.userdel,
                okValue: '确定',
                ok: function () {
                    this.title('提交中…');
                    $.ajax({
                        type: "POST",
                        url: '<?=Url::toRoute("/rbac/user/deleteall")?>',
                        data: {_csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                        datatype: "json",
                        success: function (data) {
                            data = eval("(" + data + ")");
                            switch(data.status){
                                case 403:
                                    content = authrbac.rbacerror
                                    break;
                                case 100:
                                    content = authrbac.userdel100;
                                    break;
                                case 101:
                                    content = authrbac.userdel101;
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
