<?
use app\modules\rbac\components\RouteRule;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$context = $this->context;
$labels = $context->labels();
$this->title = Yii::t('rbac-admin', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;

$rules = array_keys(Yii::$app->getAuthManager()->getRules());
$rules = array_combine($rules, $rules);
unset($rules[RouteRule::RULE_NAME]);
?>
<script src="/admin/plugins/daterangepicker/moment.min.js"></script>
<script src="/admin/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="panel-body">
                        <form role="form" class="form-horizontal" method="get" action="<?=Url::toRoute('/rbac/role/index')?>">
                            <div class="form-group">
                                <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">角色名称</label>
                                <div class="col-sm-6 col-lg-8 col-xs-12">
                                    <input type="text" placeholder="请输入角色名称" value="<?=$name?>" class="form-control" name="name">
                                </div>
                                <div class="pull-right col-xs-12 col-sm-3 col-lg-2">
                                    <button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
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
                            <th>角色名称</th>
                            <th>规则名称</th>
                            <th>描述</th>
                            <th>附加数据</th>
                            <th>创建时间</th>
                            <th style="width: 240px">操作</th>
                        </tr>
                        <?php if (!empty($roles)) { ?>
                            <?php foreach ($roles as $key => $item) { ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="usercheckbox" data-size="small" class="checkboxes">
                                    </td>
                                    <td><?= $item->name ?></td>
                                    <td><?= !empty($item->ruleName) ? $item->ruleName : '尚未添加规则名称' ?></td>
                                    <td><?= !empty($item->description) ? $item->description : '尚未添加描述' ?></td>
                                    <td><?= !empty($item->data) ? $item->data : '尚未添加附加数据' ?></td>
                                    <td><?= date('Y年m月d日 H时m分s秒', $item->createdAt) ?></td>
                                    <td>
                                        <a href="<?= Url::toRoute(['/rbac/role/update', 'id' => $item->name]) ?>"
                                           class="btn btn-info btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-eye"></i> 查看
                                        </a>
                                        <a href="<?= Url::toRoute(['/rbac/role/view', 'id' => $item->name]) ?>"
                                           class="btn btn-primary btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-pencil"></i> 权限分配
                                        </a>
                                        <button onclick="del('<?= $item->name ?>');"
                                                class="btn btn-danger btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-trash-o"></i> 删除
                                        </button>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr class="odd gradeX">
                                <td style="text-align: center" colspan="7">当前没有任何角色！</td>
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
                        <a class="btn btn-default" href="<?= Url::toRoute('/rbac/role/create') ?>"><i
                                class="fa fa-fw fa-plus-square"></i> 新增角色</a>
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
            </div>
        </div><!-- /.row -->
    </div>
</section>
<script>
    $(function () {
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
                if(chk_value.indexOf('超级管理员') > -1){
                    dialog({
                        title: prompttitle,
                        content: authrbac.roledel101,
                        okValue: '确定',
                        ok: function () {
                        },
                    }).showModal();
                }else{
                    dialog({
                        title: prompttitle,
                        content: authrbac.delrole,
                        okValue: '确定',
                        ok: function () {
                            this.title('提交中…');
                            $.ajax({
                                type: "POST",
                                url: '<?=Url::toRoute("/rbac/role/deleteselect")?>',
                                data: {ids: chk_value, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                                datatype: "json",
                                success: function (data) {
                                    data = eval("(" + data + ")");
                                    switch(data.status){
                                        case 100:
                                            content = authrbac.roledel100;
                                            break;
                                        case 101:
                                            content = authrbac.roledel101;
                                            break;
                                        case 102:
                                            content = authrbac.roledel102;
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
            }
        });
    });

    $("#delall").click(function () {
        require(["dialog"], function (dialog) {
            dialog({
                title: prompttitle,
                content: authrbac.delrole,
                okValue: '确定',
                ok: function () {
                    this.title('提交中…');
                    $.ajax({
                        type: "POST",
                        url: '<?=Url::toRoute("/rbac/role/deleteall")?>',
                        data: {_csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                        datatype: "json",
                        success: function (data) {
                            data = eval("(" + data + ")");
                            switch(data.status){
                                case 100:
                                    content = authrbac.roledel100;
                                    break;
                                case 101:
                                    content = authrbac.roledel101;
                                    break;
                                case 102:
                                    content = authrbac.roledel102;
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

    function del(name) {
        require(["dialog"], function (dialog) {
            if(name == "超级管理员"){
                dialog({
                    title: prompttitle,
                    content: authrbac.roledel101,
                    okValue: '确定',
                    ok: function () {
                    },
                }).showModal();
            }else {
                dialog({
                    title: prompttitle,
                    content: authrbac.delrole,
                    okValue: '确定',
                    ok: function () {
                        this.title('提交中…');
                        $.ajax({
                            type: "POST",
                            url: '<?=Url::toRoute("/rbac/role/delete")?>',
                            data: {id: name, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
                            datatype: "json",
                            success: function (data) {
                                data = eval("(" + data + ")");
                                switch(data.status){
                                    case 100:
                                        content = authrbac.roledel100;
                                        break;
                                    case 101:
                                        content = authrbac.roledel101;
                                        break;
                                    case 102:
                                        content = authrbac.roledel102;
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
    }
</script>
