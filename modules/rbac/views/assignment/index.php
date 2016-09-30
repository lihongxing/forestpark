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
            <div class="panel panel-info">
                <div class="panel-heading">筛选</div>
                <div class="panel-body">
                    <form id="form1" role="form" class="form-horizontal" method="get" action="<?=Url::toRoute('/rbac/assignment/index')?>">
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
                        <div class="form-group">
                        </div>
                    </form>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?=Yii::t('rbac-admin', 'Users');?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>编号</th>
                            <th>用户名</th>
                            <th>邮箱</th>
                            <th>手机号码</th>
                            <th>创建时间</th>
                            <th style="width: 120px">操作</th>
                        </tr>
                        <?php if (!empty($users)) { ?>
                            <?php foreach ($users as $key => $item) { ?>
                                <tr class="odd gradeX">
                                    <td><?= $item['id']?></td>
                                    <td><?= $item['username'] ?></td>
                                    <td><?= $item['email'] ?></td>
                                    <td><?= $item['mobile'] ?></td>
                                    <td><?= date('Y年m月d日 H时m分s秒',$item['created_at']) ?></td>
                                    <td>
                                        <a href="<?=Url::toRoute(['/rbac/assignment/view','id'=> $item['id']])?>"  class="btn btn-primary btn-sm checkbox-toggle" type="button">
                                            <i class="fa fa-unlock-alt"></i></i>分配权限
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
