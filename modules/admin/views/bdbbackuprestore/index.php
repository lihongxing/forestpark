<?
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\web\widget;
$this->title = Yii::t('admin', 'dbbackuprestorelist');
?>
<link rel="stylesheet" href="/admin/plugins/iCheck/flat/blue.css">

<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i><?=Yii::t('admin', 'dbbackuprestorelist');?></a></li>
    </ol>
</section>

<!-- Main content -->

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">筛选</div>
                <div class="panel-body">
                    <form id="form1" role="form" class="form-horizontal" method="get" action="<?=Url::toRoute('/admin/bdbbackuprestore/index')?>">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">备份信息</label>
                            <div class="col-sm-8 col-lg-9 col-xs-12">
                                <input type="text" placeholder="可搜索表名称,描述" value="<?=$GET['name']?>" name="keyword" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label">备份时间</label>
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
                        <div class="form-group">
                        </div>
                    </form>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=Yii::t('admin', 'dbbackuprestorelist');?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th width="5%">
                                <!-- Check all button -->
                                <button style="padding:1px 4px" id="delsqlbackstore" class="btn btn-default btn-sm checkbox-toggle"><i
                                        class="fa fa-square-o"></i>
                                </button>
                            </th>
                            <th>描述</th>
                            <th>名称</th>
                            <th>大小</th>
                            <th>创建时间</th>
                            <th>备份的数据表名称</th>
                            <th width="25%">操作</th>
                        </tr>
                        <?php if (!empty($sqlbackstores)) { ?>
                            <?php foreach ($sqlbackstores as $key => $item) { ?>
                                <tr class="odd gradeX" id="sqlbackstorelist">
                                    <td><input type="checkbox"  class="checkboxes" value=<?= $item['id']?> ></td>
                                    <td><?= $item['sql_des']?></td>
                                    <td><?= $item['sql_name'] ?></td>
                                    <td><?= $item['sql_size'] ?>KB</td>
                                    <td><?= date('Y年m月d日 H时m分s秒',$item['sql_addtime']) ?></td>
                                    <td><?= $item['sql_content'] ?></td>
                                    <td>
                                        <button onclick="deletebackup(<?= $item['id'] ?>);"
                                                class="btn btn-primary btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-trash-o"></i> 删除
                                        </button>
                                        <button onclick="restorebackup('<?= $item['sql_name'] ?>');"
                                                class="btn btn-primary btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-reply"></i>还原备份
                                        </button>
                                        <a href="<?=Url::toRoute(['/admin/bdbbackuprestore/download', 'file' => $item['sql_name']])?>"
                                           class="btn btn-primary btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-download"></i>导出备份
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr class="odd gradeX">
                                <td style ="text-align: center" colspan="7">当前没有任何备份！为了及时有效的恢复数据，请您尽快添加备份！</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer clearfix">
                    <div class="btn-group">
                        <a style="width: 80px" class="btn btn-primary"
                           href="#" id="delbackup"> 删除选中</a>
                        <a class="btn btn-default" href="#" id="delbackups"> 删除全部</a>
                        <a class="btn btn-default" href="<?=Url::toRoute('/admin/bdbbackuprestore/form')?>"><i
                                class="fa fa-fw fa-plus-square"></i> 新增备份</a>
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
<script src="/admin/plugins/iCheck/icheck.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#sqlbackstorelist, #sqltableslist input[type="checkbox"]').iCheck({
            checkboxClass: 'icheckbox_flat-blue',
            radioClass: 'iradio_flat-blue'
        });

        $("#addsqlbackstore").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                $("#sqltableslist input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                $("#sqltableslist input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });

        $("#delsqlbackstore").click(function () {
            var clicks = $(this).data('clicks');
            if (clicks) {
                $("#sqlbackstorelist input[type='checkbox']").iCheck("uncheck");
                $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
            } else {
                $("#sqlbackstorelist input[type='checkbox']").iCheck("check");
                $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
            }
            $(this).data("clicks", !clicks);
        });

        $(".mailbox-star").click(function (e) {
            e.preventDefault();
            var $this = $(this).find("a > i");
            var glyph = $this.hasClass("glyphicon");
            var fa = $this.hasClass("fa");
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
    function deletebackup(id) {
        dialog({
            title: prompttitle,
            content: databasedelete,
            okValue: '确定',
            ok: function () {
                this.title('提交中…');
                $.ajax({
                    //提交数据的类型 POST GET
                    type: "POST",
                    //提交的网址
                    url: "<?=Url::toRoute("/admin/bdbbackuprestore/delete")?>",
                    //提交的数据
                    data: {id: id, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                    //返回数据的格式
                    datatype: "json",//"xml", "html", "script", "json", "jsonp", "text".
                    //在请求之前调用的函数
                    //成功返回之后调用的函数
                    success: function (data) {
                        data = eval("(" + data + ")");
                        if(data.status == 403){
                            content = '对不起您尚未获得此权限'
                        }else if(data.status == true){
                            content = databasebackupsucc
                        }else{
                            content = databasebackuperror
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

    $("#delbackup").click(function () {
        //获取选中需要备份的表的表名称
        var chk_value = [];
        $("#sqlbackstorelist .checked").each(function () {
            chk_value.push($(this).find('input').val());
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
                content: databasedelete,
                okValue: '确定',
                ok: function () {
                    this.title('提交中…');
                    $.ajax({
                        //提交数据的类型 POST GET
                        type: "POST",
                        //提交的网址
                        url: "<?=Url::toRoute("/admin/bdbbackuprestore/deletes")?>",
                        //提交的数据
                        data: {ids: chk_value, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                        //返回数据的格式
                        datatype: "json",
                        success: function (data) {
                            data = eval("(" + data + ")");
                            if(data.status == 403){
                                content = '对不起您尚未获得此权限'
                            }else if(data.status == true){
                                content = delsuccess+data.success+ge + delerror+data.error+ge
                            }else{
                                content = delsuccess+data.success+ge + delerror+data.error+ge
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

    $("#delbackups").click(function () {
        dialog({
            title: prompttitle,
            content: databasedelete,
            okValue: '确定',
            ok: function () {
                this.title('提交中…');
                $.ajax({
                    //提交数据的类型 POST GET
                    type: "POST",
                    //提交的网址
                    url: "<?=Url::toRoute("/admin/bdbbackuprestore/deletes")?>",
                    //提交的数据
                    data: {ids: 'all', _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                    //返回数据的格式
                    datatype: "json",
                    success: function (data) {
                        data = eval("(" + data + ")");
                        dialog({
                            title: prompttitle,
                            content: delsuccess+data.success+ge + delerror+data.error+ge,
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

    function restorebackup(filename) {
        dialog({
            title: prompttitle,
            content: restoredatabackup,
            okValue: '确定',
            ok: function () {
                this.title('提交中…');
                $.ajax({
                    //提交数据的类型 POST GET
                    type: "POST",
                    //提交的网址
                    url: "<?=Url::toRoute("/admin/bdbbackuprestore/restore")?>",
                    //提交的数据
                    data: {filename: filename, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                    //返回数据的格式
                    datatype: "json",//"xml", "html", "script", "json", "jsonp", "text".
                    //在请求之前调用的函数
                    //成功返回之后调用的函数
                    success: function (data) {
                        data = eval("(" + data + ")");
                        dialog({
                            title: prompttitle,
                            content: data.status ? restoredatabackupsucc : restoredatabackuperror,
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
</script>