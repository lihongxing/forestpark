<?
$this->title = yii::$app->params['siteinfo']['sitename']. '-欢迎';
$this->params['breadcrumbs'][] = '欢迎';
?>
<!-- Main content -->
<section class="content">
    <div class="error-page">
        <h2 class="text-primary" style="font-size: 50px;margin-top: 80px"> <?= yii::$app->params['siteinfo']['sitename']?>欢迎您</h2>
        <div class="error-content" style="margin-left: 9px">
            <h3><i class="fa fa-warning text-yellow"></i> 特别提醒!</h3>
            <p>
                如果您找不到您在找的那一页。同时，您可以
                <a href="../../index.html"></a> 尝试使用搜索表单。
            </p>
            <form class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="搜索...">
                    <div class="input-group-btn">
                        <button type="submit" name="submit" class="btn btn-primary btn-flat"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <!-- /.input-group -->
            </form>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section><!-- /.content -->