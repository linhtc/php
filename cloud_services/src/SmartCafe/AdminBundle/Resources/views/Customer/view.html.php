<?php $view->extend('SmartCafeAdminBundle::admin.layout.html.php') ?>
<?php $view['slots']->start('js') ?>
<script type="text/javascript" src="/web/js/admin/customer.js" ></script>
<script>
	var getListLink = '<?php echo $view['router']->generate('sc_admin_customer_hidden_get_list'); ?>';
	var getListPage = 0;
	Customer.getList(getListLink, getListPage);
</script>
<?php $view['slots']->stop() ?>
<!-- Main Content Here -->
<?php $view['slots']->start('body') ?>
<div class="row">
	<div class="col-md-12 ">
		<!-- BEGIN SAMPLE FORM PORTLET-->
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green">
					<i class="icon-pin font-green"></i>
					<span class="caption-subject bold uppercase"> Management</span>
				</div>
				<div class="actions">
					<a class="btn btn-circle btn-icon-only blue" href="javascript:;" title="Create">
					<i class="icon-cloud-upload"></i>
					</a>
					<a class="btn btn-circle btn-icon-only green" href="javascript:;" title="Edit">
					<i class="icon-wrench"></i>
					</a>
					<a class="btn btn-circle btn-icon-only red" href="javascript:;" title="Delete">
					<i class="icon-trash"></i>
					</a>
					<a class="btn btn-circle btn-icon-only blue-madison" href="javascript:;" title="Search">
					<i class="fa fa-search"></i>
					</a>
					<a class="btn btn-circle btn-icon-only blue-hoki" href="javascript:;" title="Reset">
					<i class="fa fa-retweet"></i>
					</a>
					<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title="">
					</a>
				</div>
			</div>
			<div class="portlet-body form">
				<form role="form">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-md-line-input has-success">
								<div class="input-icon right">
									<input type="text" class="form-control" placeholder="Input customer name...">
									<label for="form_control_1">Customer name</label>
									<span class="help-block">Please input...</span>
									<i class="fa fa-ellipsis-h"></i>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group form-md-line-input has-success">
								<div class="input-icon right">
									<input type="text" class="form-control" placeholder="Input address...">
									<label for="form_control_1">Address</label>
									<span class="help-block">Please input...</span>
									<i class="fa fa-ellipsis-h"></i>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group form-md-line-input has-success">
								<div class="input-icon right">
									<input type="text" class="form-control" placeholder="Input phone...">
									<label for="form_control_1">Phone</label>
									<span class="help-block">Please input...</span>
									<i class="fa fa-ellipsis-h"></i>
								</div>
							</div>
						</div>
					</div>
					<br />
					<div class="form-actions noborder" style="display:none;">
						<button type="button" class="btn blue">Submit</button>
						<button type="button" class="btn default">Cancel</button>
					</div>
				</form>
			</div>
		</div>
		<!-- END SAMPLE FORM PORTLET-->
	</div>
</div>
<div class="clearfix">
</div>
<div class="row">
	<div class="col-md-12 ">
		<!-- BEGIN SAMPLE FORM PORTLET-->
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption font-green">
					<i class="icon-pin font-green"></i>
					<span class="caption-subject bold uppercase"> Summary</span>
				</div>
				<div class="actions">
					<a class="btn btn-circle btn-icon-only blue" href="javascript:;" title="Export">
					<i class="icon-cloud-upload"></i>
					</a>
					<a class="btn btn-circle btn-icon-only blue-hoki" href="javascript:;" title="Reload">
					<i class="fa fa-retweet"></i>
					</a>
					<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title="">
					</a>
				</div>
			</div>
			<div class="portlet-body form">
				<form role="form">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive list-container">
							
							</div>
						</div>
					</div>
					<div class="form-actions noborder">
						
					</div>
				</form>
			</div>
		</div>
		<!-- END SAMPLE FORM PORTLET-->
	</div>
</div>
<?php $view['slots']->stop() ?>