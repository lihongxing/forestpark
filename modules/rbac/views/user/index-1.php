<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\rbac\components\Helper;

$this->title = Yii::t('rbac-admin', 'Users');
$this->params['breadcrumbs'][] = $this->title;

?>
<section class="content-header">
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Data tables</li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box  box-primary collapsed-box">
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'username',
                            'email:email',
                            'created_at:date',
                            [
                                'attribute' => 'status',
                                'value' => function($model) {
                                    return $model->status == 0 ? 'Inactive' : 'Active';
                                },
                                'filter' => [
                                    0 => '禁用',
                                    10 => '启用'
                                ]
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{user-view} {user-update} {user-delete}',
                                'buttons' => [
                                    // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                                    'user-view' => function ($url, $model, $key) {
                                        $options = [
                                            'class' =>'btn btn-primary btn-sm',
                                            'title' => Yii::t('yii', 'View'),
                                            'aria-label' => Yii::t('yii', 'View'),
                                            'data-pjax' => '0',
                                        ];
                                        return Html::a('<span class="glyphicon glyphicon-eye-open">查看</span>', $url, $options);
                                    },
                                    'user-update' => function ($url, $model, $key) {
                                        $options = [
                                            'class' =>'btn btn-primary btn-sm',
                                            'title' => Yii::t('yii', 'Update'),
                                            'aria-label' => Yii::t('yii', 'Update'),
                                            'data-pjax' => '0',
                                        ];
                                        return Html::a('<span class="glyphicon glyphicon-pencil">编辑</span>', $url, $options);
                                    },
                                    'user-delete' => function ($url, $model, $key) {
                                        $options = [
                                            'class' =>'btn btn-primary btn-sm',
                                            'title' => Yii::t('yii', 'Delete'),
                                            'aria-label' => Yii::t('yii', 'Delete'),
                                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                            'data-method' => 'post',
                                            'data-pjax' => '0',
                                        ];
                                        return Html::a('<span class="glyphicon glyphicon-trash">删除</span>', $url, $options);
                                    },
                                ]
                            ],
                        ],
                    ]); ?>
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</section>
