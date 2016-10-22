<?php
use yii\helpers\Html;
$this->title = Yii::t('admin', 'videoplayadd');
$this->params['breadcrumbs'][] = ['label' => Yii::t('admin', 'videoplaylist'), 'url' => ['videoplay-list']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript" src="/admin/js/tooltipbox.js"></script>
<link rel="stylesheet" href="/admin/plugins/iCheck/all.css">
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=$this->title ?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <form action="" method="post" class="form-horizontal" role="form" enctype="multipart/form-data" id="form">
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-2 col-md-2 col-lg-2 control-label">视屏播放标题</label>
                            <div class="col-sm-9 col-xs-12">
                                <input name="Videoplay[vid_title]" class="form-control" value="<?=$Videoplay['vid_title']?>" type="text" placeholder="请输入视屏播放标题">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">视屏播放详情</label>
                            <div class="col-sm-9 col-xs-12">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'Videoplay[vid_describe]', 'type' => 'content', 'value' => $Videoplay['vid_describe'],'options' => array('width' => 827, 'module' => 'admin')]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">添加时间</label>
                            <div class="col-sm-9 col-xs-12">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'Videoplay[vid_addtime]', 'type' => 'timestart', 'value' => !empty($Videoplay['vid_addtime']) ? date('Y-m-d H:i',$Videoplay['vid_addtime']) : date('Y-m-d H:i'),'options' => 1]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="inputPassword3">视屏播放排序</label>
                            <div class="col-sm-9">
                                <input  onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}"class="form-control" value="<?=empty($Videoplay['vid_order']) ? 1:$Videoplay['vid_order'] ?>" placeholder="请输入视屏播放排序" type="number" min="1" max="99" name="Videoplay[vid_order]" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-12 col-sm-3 col-md-2 control-label">视屏播放发布人</label>
                            <div class="col-sm-4">
                                <div class='input-group'>
                                    <input type="text" name="releaseuser[username]" maxlength="30" value="<?=$releaseuser['username']?>" id="releaseuserusername" class="form-control" readonly />
                                    <div class='input-group-btn'>
                                        <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-releaseuser-notice').modal();">选择视屏播放发布人</button>
                                        <button class="btn btn-danger" type="button" onclick="$('#vid_releaseuser').val('');$('#releaseuserusername').val('');$('#releaseuserheadimg').hide()">清除选择</button>
                                    </div>
                                </div>
                                <input type="hidden" value="<?=$Videoplay['vid_release_uid']?>" id='vid_releaseuser' name="Videoplay[vid_release_uid]" class="form-control"  />
                                <span id="releaseuserheadimg" class='help-block' <?php if(empty($Videoplay['vid_release_uid'])){?> style="display:none"<?php }?> ><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?=$releaseuser['head_img']?>"/></span>
                                <div id="modal-module-releaseuser-notice"  class="modal fade" tabindex="-1">
                                    <div class="modal-dialog" style='width: 660px;'>
                                        <div class="modal-content">
                                            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>选择视屏播放发布人</h3></div>
                                            <div class="modal-body" >
                                                <div class="row">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="keyword" value="" id="search-kwd1-notice" placeholder="请输入昵称/姓名/手机号" />
                                                        <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_releaseuser();">搜索</button></span>
                                                    </div>
                                                </div>
                                                <div id="module-releaseuser-notice" style="padding-top:5px;"></div>
                                            </div>
                                            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                        </div>
                                    </div>
                                    <script language='javascript'>
                                        function search_releaseuser() {
                                            if( $.trim($('#search-kwd1-notice').val())==''){
                                                Tip.focus('#search-kwd1-notice','请输入关键词');
                                                return;
                                            }
                                            $("#module-releaseuser-notice").html("正在搜索....")
                                            $.get("<?=\yii\helpers\Url::toRoute('/rbac/user/search')?>", {
                                                keyword: $.trim($('#search-kwd1-notice').val()),select: 'select_releaseuser'
                                            }, function(dat){
                                                $('#module-releaseuser-notice').html(dat);
                                            });
                                        }
                                        function select_releaseuser(o) {
                                            $("#vid_releaseuser").val(o.id);
                                            $("#releaseuserheadimg").show();
                                            $("#vid_releaseuser-error").remove();
                                            $("#releaseuserheadimg").find('img').attr('src',o.head_img);
                                            $("#releaseuserusername").val( o.username);
                                            $("#modal-module-releaseuser-notice .close").click();
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" for="">视频链接</label>
                            <div class="col-sm-9">
                                <?= \xiaohei\widgetform\FormWidget::widget(['name' => 'Videoplay[vid_url]', 'type'=>'video', 'value' => $Videoplay['vid_url'], 'options' => array('extras' => array('text' => 'class = "form-control ignore"'), 'module' => 'admin')]) ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-md-offset-2 col-lg-offset-1 col-xs-12 col-sm-9 col-md-10 col-lg-21">
                                <input type="submit" class="btn btn-primary col-lg-1" value="<?=empty($Videoplay['vid_id'])? '新增':'修改'?>" name="add" id="add" data-original-title="" title="">
                                <input type="hidden" value="<?=yii::$app->request->getCsrfToken()?>" name="_csrf">
                                <input type="hidden" value="<?=$Videoplay['vid_id']?>" name="vid_id">
                                <input type="button" class="btn btn-default col-lg-2" value="返回列表"
                                       style="margin-left:10px;" onclick="history.back()" name="back">
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.box -->
        </div>
    </div>
</section>
<script>
    $(function () {
        require(["validation", "validation-methods"], function (validate) {
            $("#form").validate({
                ignore: ".ignore",
                rules: {
                    "Videoplay[vid_title]": {
                        required: true,
                        minlength: 2,
                        maxlength: 30
                    },
                    "Videoplay[vid_releaseuser]": {
                        required: true,
                    }

                },

                messages: {
                    "Videoplay[vid_title]": {
                        required: "请输入视屏播放标题",
                        minlength: "视屏播放标题不能小于2个字符",
                        maxlength: "视屏播放标题不能大于30个字符",
                    },
                    "Videoplay[vid_releaseuser]": {
                        required: "请选择视屏播放发布人",
                    }
                },
                errorClass: "has-error",
            });
        });
    });
    $('input[type="checkbox"].minimal-blue, input[type="radio"].minimal-blue').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });
</script>
