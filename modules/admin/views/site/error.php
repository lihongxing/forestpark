<!-- Content Header (Page header) -->
<?
preg_match_all("/[^\(]*\(\D*(\d*).*/i",$name,$str_ary);
$this->title = yii::$app->params['siteinfo']['sitename']. '404错误';
$this->params['breadcrumbs'][] = $str_ary[1][0]; ;
if($str_ary[1][0] == 404){
	$message = '对不起! 您访问的页面不存在！';
}
use yii\helpers\Html;
?>
<!-- Main content -->
<section class="content">
<div class="error-page">
	<h2 class="headline text-yellow"><?=$str_ary[1][0]; ?></h2>
	<div class="error-content">
		<h3><i class="fa fa-warning text-yellow"></i><?=$message?></h3>
		<p>
                                   我们找不到你在找的那一页。同时，你可以
			<a href="../../index.html">返回到上一页</a> 或者尝试使用搜索表单。
		</p>
		<form class="search-form">
			<div class="input-group">
				<input type="text" name="search" class="form-control" placeholder="Search">
				<div class="input-group-btn">
					<button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
				</div>
			</div>
			<!-- /.input-group -->
		</form>
	</div>
	<!-- /.error-content -->
</div>
<!-- /.error-page -->
</section><!-- /.content -->