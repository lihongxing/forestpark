<?php 
use yii\helpers\Url;
use xiaohei\widgetform\FormWidget;
?>
<section class="content-header">
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>站点管理</a></li>
		<li class="active">站点设置</li>
	</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
				    <li class="active"><a class="active" href="<?=Url::toRoute("/admin/site/index") ?>">基本信息设置</a></li>
					<li><a href="#activity" >平台支付设置</a></li>
					<li><a href="#timeline" >短信接口</a></li>
					<li><a href="#settings" >在线支付接口</a></li>
					<li><a href="<?=Url::toRoute("/admin/site/upfile") ?>">附件设置</a></li>
					<li><a href="#settings">安全设置</a></li>
					
				</ul>
				<div class="tab-content">
					<div>
						<form class="form-horizontal">
							<div class="form-group">
								<label for="inputName" class="col-sm-2 control-label">网站名称</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="site_name" placeholder="请输入站点名称">
								</div>
							</div>
							<div class="form-group">
								<label for="inputEmail" class="col-sm-2 control-label">网站标题</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="site_title" placeholder="请输入网站标题">
								</div>
							</div>
							<div class="form-group">
								<label for="inputName" class="col-sm-2 control-label">网站logo</label>
								<div class="col-sm-10">
									<?= FormWidget::widget(['name' => 'thumb', 'value' => '', 'default' => '', 'options' => array('width' => 400, 'extras' => array('text' => 'ng-model="entry.thumb"'))]) ?>
								</div>
							</div>
							<div class="form-group">
								<label for="inputName" class="col-sm-2 control-label">网站网址</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="site_url" placeholder="请输入网站网址">
								</div>
							</div>
							<div class="form-group">
								<label for="inputName" class="col-sm-2 control-label">网站二维码</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="site_twm" placeholder="请输入网站二维码">
								</div>
							</div>
							<div class="form-group">
								<label for="inputName" class="col-sm-2 control-label">顶级域名</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="server_topdomain" placeholder="请输入顶级域名">
								</div>
							</div>
							<div class="form-group">
								<label for="inputName" class="col-sm-2 control-label">审核客户</label>
								<div class="col-sm-10">
									<input class="form-control" type="checkbox" checked>
								</div>
							</div>
							<div class="form-group">
								<label for="inputName" class="col-sm-2 control-label">注册需要手机号</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="inputName" placeholder="Name">
								</div>
							</div>
							<div class="form-group">
								<label for="inputName" class="col-sm-2 control-label">站长QQ</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="inputName" placeholder="Name">
								</div>
							</div>
							<div class="form-group">
								<label for="inputName" class="col-sm-2 control-label">站长手机</label>
								<div class="col-sm-10">
									<input type="text" class="form-control" id="inputName" placeholder="Name">
								</div>
							</div>
							<div class="form-group">
								<label for="inputExperience" class="col-sm-2 control-label">网站关键词</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label for="inputExperience" class="col-sm-2 control-label">底部版权</label>
								<div class="col-sm-10">
									<textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<button type="submit" class="btn btn-danger">Submit</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>



<script type="text/javascript">
$(function(argument) {
	$('[type="checkbox"]').bootstrapSwitch();
})
</script>

