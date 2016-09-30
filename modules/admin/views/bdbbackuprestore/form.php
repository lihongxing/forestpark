<?
use yii\helpers\Url;
$this->title = Yii::t('admin', 'dbbackuprestoreadd');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'dbbackuprestoremanage'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>

    .dd-handle {
        height: 40px;
        line-height: 30px;
        width: 100px;
    }
    .field-item {
        -moz-user-select: none;
        border: 1px solid #ccc;
        border-radius: 3px;
        cursor: pointer;
        float: left;
        margin: 5px;
        padding: 10px;
        position: relative;
    }
    .field-item:active {
        background: #d9d9d9 none repeat scroll 0 0;
    }
    .drag {
        background: #d9d9d9 none repeat scroll 0 0;
    }
    .form-control .select2-choice {
        border: 0 none;
        border-radius: 2px;
        height: 32px;
        line-height: 32px;
    }
    .field-item.field-item-remove span {
        color: red;
        cursor: pointer;
        position: absolute;
        right: -5px;
        top: -10px;
    }
</style>
<link rel="stylesheet" href="/admin/plugins/iCheck/all.css">
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $this->title ?></h3>
                </div><!-- /.box-header -->
                <form enctype="multipart/form-data" class="form-horizontal" id="addsqlbackstore" method="post" action="<?=Url::toRoute('/admin/bdbbackuprestore/form')?>">
                    <div class="">
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="col-xs-12 col-sm-3 col-md-2 control-label"><span
                                        style="color:red">*</span>备份名称</label>
                                <div class="col-sm-9 col-xs-12">
                                    <input type="text" value="" id="db_name" placeholder="请输入备份名称" class="form-control"
                                           name="Sqlbackstore[name]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-12 col-sm-3 col-md-2 control-label">选择需要备份的表</label>
                                <div class="col-sm-9 col-xs-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            删除备份表(点击删除)
                                        </div>
                                        <div id="add_fields" class="panel-body ui-sortable">
                                            <?php if(!empty($brandlistno)){?>
                                                <?php foreach($brandlistno as $key => $item){?>
                                                    <div data-subtitle="" data-width="12"  data-slider-value="<?= $item['brand_id']?>" data-title="<?= $item['brand_name']?>"
                                                         data-field="couponprice" class="field-item field-item-remove"><?= $item['brand_name']?>
                                                        <span><i class="fa fa-remove"></i></span><input type="hidden" name="Sqlbackstore[dbname][]" value="<?= $item['brand_id']?>"/>
                                                    </div>
                                                <?php }?>
                                            <?php }?>
                                        </div>
                                        <div class="panel-heading">
                                            增加备份表 (点击增加)
                                        </div>
                                        <div id="new_fields" class="panel-body">
                                            <?php if(!empty($tables)){?>
                                                <?php foreach($tables as $key => $item){?>
                                                    <div data-subtitle="" data-width="12"  data-slider-value="<?= $item['name']?>" data-title="<?= $item['comment']?>"
                                                         data-field="couponprice" class="field-item field-item-add"><?= $item['comment']?>
                                                    </div>
                                                <?php }?>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-12 col-sm-3 col-md-2 control-label"></label>
                                <div class="col-sm-9 col-xs-12">
                                    <input type="button" class="btn btn-primary col-lg-1" value="新增" name="addbackup" id="addbackup" data-original-title="" title="">
                                    <input type="hidden" value="<?=yii::$app->request->getCsrfToken()?>" name="_csrf">
                                    <input type="button" class="btn btn-default col-lg-2" value="返回列表"
                                           style="margin-left:10px;" onclick="history.back()" name="back">
                                    <input type="button" class="btn btn-default col-lg-2" value="备份全部"
                                           style="margin-left:10px;" id="addbackups" name="back">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
</section><!-- /.content -->
<script>
    $('input[type="checkbox"].minimal-blue, input[type="radio"].minimal-blue').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
    $("#add_fields").on('click','div i',function () {
        data  = new Object();
        element = $(this).parent().parent()
        data.field = 'field-item field-item-add';
        data.title = element.attr('data-title');
        data.width = element.attr('data-width');
        data.subtitle = element.attr('data-subtitle');
        data.value = element.attr('data-slider-value');
        addData(data,true)
        element.remove();
    });
    $("#new_fields").on('click','div',function () {
        data  = new Object();
        data.field = 'field-item field-item-add';
        data.title = $(this).attr('data-title');
        data.width = $(this).attr('data-width');
        data.subtitle = $(this).attr('data-subtitle');
        data.value = $(this).attr('data-slider-value');
        addData(data,false)
        $(this).remove();
    });
    function addData(data,other){
        var html = '';
        if(!other){
            html = '<div class="field-item field-item-remove" data-slider-value="' + data.value+'"  data-field="' + data.field+'"  data-title="' +data.title+'" data-width="' + data.width +'" data-subtitle="' +( data.subtitle || "" )+'">' +( data.subtitle || data.title ) + ' <span><i class="fa fa-remove"></i></span><input type="hidden" name="Sqlbackstore[dbname][]" value="' + data.value+'"/></div>';
            $('#add_fields').append(html);
        }else{
            html = '<div class="field-item field-item-add" data-slider-value="' + data.value+'" data-field="' + data.field+'"  data-title="' +data.title+'" data-width="' + data.width +'" data-subtitle="' +( data.subtitle || "" )+'">' +( data.subtitle || data.title ) + '</div>';
            $('#new_fields').append(html);
        }
    }
    $(function () {
        require(["validation", "validation-methods"], function (validate) {
            $("#addsqlbackstore").validate({
                rules: {
                    "Sqlbackstore[name]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                },
                messages: {
                    "Sqlbackstore[name]": {
                        required: "请输入备份名称",
                        minlength: "备份名称不能小于2个字符",
                        maxlength: "备份名称不能大于30个字符",
                    },
                },
                errorClass: "has-error",
            });
        });
    });


    $("#addbackups").click(function () {
        dialog({
            title: prompttitle,
            content: databasebackup,
            okValue: '确定',
            ok: function () {
                this.title('提交中…');
                $.ajax({
                    //提交数据的类型 POST GET
                    type: "POST",
                    //提交的网址
                    url: "<?=Url::toRoute("/admin/bdbbackuprestore/form")?>",
                    //提交的数据
                    data: {tables: 'all', db_name: '全部备份', _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
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
    });

    $("#addbackup").click(function () {
        //获取选中需要备份的表的表名称
        var chk_value = [];
        $("#add_fields .field-item").each(function () {
            chk_value.push($(this).attr('data-slider-value'));
        });
        var db_name = $('#db_name').val();
        if (db_name == '') {
            dialog({
                title: prompttitle,
                content: '请输入备份名称',
                cancel: false,
                okValue: '确定',
                ok: function () {
                }
            }).showModal();
            return;
        }
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
                content: databasebackup,
                okValue: '确定',
                ok: function () {
                    this.title('提交中…');
                    $.ajax({
                        //提交数据的类型 POST GET
                        type: "POST",
                        //提交的网址
                        url: "<?=Url::toRoute("/admin/bdbbackuprestore/form")?>",
                        //提交的数据
                        data: {tables: chk_value ,db_name: db_name, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
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
    });
</script>