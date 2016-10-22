<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this \yii\web\View */
/* @var $content string */
?>
<header class="main-header">
    <?= Html::a('<span class="logo-mini">'.yii::$app->params['siteinfo']['sitenameabbreviat'].'</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->
                <li class="dropdown messages-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-envelope-o"></i>
                        <span class="label label-success"><?=empty($messages) ? 0 : $count1+$count2?></span>
                        站内消息
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">您收到 <?=empty($messages) ? 0 : $count1+$count2?> 消息</li>
                        <li>
                            <ul class="menu">
                                <!-- start message -->
                                <?php if(!empty($messages)){?>
                                    <?php foreach($messages as $key => $item){?>
                                        <li>
                                            <a href="#" onclick="showmessage(<?=$item['mes_id']?>)">
                                                <div class="pull-left">
                                                    <img src="<?= $item['release_user']['head_img'] ?>" class="img-circle"
                                                         alt="User Image"/>
                                                </div>
                                                <h4>
                                                    <?=$item['release_user']['username']?>
                                                    <small><i class="fa fa-clock-o"></i> <?=$item['mes_addtime']?></small>
                                                </h4>
                                                <p><?=mb_substr($item['mes_title'],0,24,'utf-8')?>...</p>
                                            </a>
                                        </li>
                                    <?php }?>
                                <?php }?>
                                <!-- end message -->
                            </ul>
                        </li>
                        <li class="footer"><a href="<?=\yii\helpers\Url::toRoute(['/rbac/user/profile', 'id' => yii::$app->user->identity->id, 'flag' => 'message'])?>">看到所有的消息</a></li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img onerror='this.src="<?=$directoryAsset?>/img/user2-160x160.jpg"'; src='<?= empty(yii::$app->user->identity->head_img) ? "$directoryAsset./img/user2-160x160.jpg": yii::$app->user->identity->head_img?>' class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?=yii::$app->user->identity->username?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img onerror='this.src="<?=$directoryAsset?>/img/user2-160x160.jpg"'; src='<?= empty(yii::$app->user->identity->head_img) ? "$directoryAsset./img/user2-160x160.jpg": yii::$app->user->identity->head_img?>' class="img-circle"
                                 alt="User Image"/>
                            <p>
                                <?php
                                    $roles = \GuzzleHttp\json_decode(yii::$app->user->identity->role);
                                    $rolestr = '';
                                    if(empty($roles)){
                                        $roles[0] = '未设置';
                                    }
                                    foreach($roles as $key => $item){
                                        if($key == 0){
                                            $rolestr = $rolestr.$item;
                                        }else{
                                            $rolestr = $rolestr.'|'.$item;
                                        }
                                    }
                                ?>
                                <?=yii::$app->user->identity->username?> - <?=$rolestr?>
                                <small><?=date('Y年m月d日 H时i分s秒',yii::$app->user->identity->created_at)?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?=\yii\helpers\Url::toRoute(['/rbac/user/profile', 'id' => yii::$app->user->identity->id, 'flag' => 'profile'])?>" class="btn btn-default btn-flat">个人资料</a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    '退出登录',
                                    ['/rbac/user/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i>站点设置</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<div id="modal-module-message"  class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button><h3>消息详细信息</h3></div>
            <div class="modal-body" >
                <div class="user-block">
                    <img class="img-circle img-bordered-sm" id="mes_release_user_head_img" src="" alt="user image">
                        <span class="username">
                            <a href="#" id="mes_release_user_username">Jonathan Burke Jr.</a>
                        </span>
                    <span class="description" id="mes_addtime"></span>
                </div>
                <strong>
                    <i class="fa fa-book margin-r-5"></i>
                    <d id="mes_title"></d>
                </strong>
                <p id="mes_content">
                </p>
                <ul class="list-inline">
                    <li id="mes_status"></li>
                    </li>
                    <li class="pull-right">
                        <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i>
                            <d id="mes_times"></d>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function showmessage(mes_id){
        //获取消息详细信息
        $.ajax({
            type: "POST",
            url: '<?=Url::toRoute("/rbac/message/details")?>',
            //提交的数据
            data: {mes_id: mes_id, _csrf: "<?=yii::$app->request->getCsrfToken()?>"},
            datatype: "json",
            success: function (data) {
                data = eval("(" + data + ")");
                var content = '';
                switch(data.status){
                    case 403:
                        content = '对不起，您还没有获得查看消息详细信息的权限！';
                        dialog({
                            title: prompttitle,
                            content: content,
                            cancel: false,
                            okValue: '确定',
                            ok: function () {
                                window.location.reload();
                            }
                        }).showModal();
                        break;
                    case 100:
                         $("#mes_release_user_head_img").attr('src',data.mesinfo['head_img']);
                         $("#mes_release_user_username").text(data.mesinfo['username']);
                         $("#mes_addtime").text('消息发送的时间-'+data.mesinfo['mes_addtime']);
                         $("#mes_title").text(data.mesinfo['mes_title']);
                         $("#mes_content").text(data.mesinfo['mes_content']);
                         $("#mes_times").text('查看次数（'+data.mesinfo['mes_times']+'）');
                         if(data.mesinfo['mes_class'] == 1){
                             var mes_status = '<a href="'+data.url+'" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> 去审核</a>';
                         }
                         $("#mes_status").empty().html(mes_status);
                         $('#modal-module-message').modal();
                         break;
                }

            }
        });
    }
</script>