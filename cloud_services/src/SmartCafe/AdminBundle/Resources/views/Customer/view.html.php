<?php $view->extend('SmartCafeAdminBundle::admin.layout4.html.php') ?>
<?php $view['slots']->start('js') ?>
<script type="text/javascript" src="/web/js/admin/customer.js" ></script>
<script type="text/javascript" src="/web/js/SmartCafe/admin/optimize.js" ></script>
<script>
	//Define global variables for this page
	var getListLink = '<?php echo $view['router']->generate('sc_admin_customer_hidden_get_list'); ?>';
	var getListPage = 0;
	var gridView = 'control_container';
	var formView = 'control_form';
	var rowManagement = 'row_ui_management';
	var rowSummary = 'row_ui_summary';
	
	Optimize.initFormControl();
	Optimize.getDataWithAjax(1);
	$('#control_search').click(function(){
		Optimize.getDataWithAjax(1);
	});
</script>
<?php $view['slots']->stop() ?>
<!-- Main Content Here -->
<?php $view['slots']->start('body') ?>
<div class="row">
	<div class="col-md-12 ">
		<!-- BEGIN SAMPLE FORM PORTLET-->
		<div class="portlet light bordered" id="row_ui_management">
			<div class="portlet-title">
				<div class="caption font-green">
					<i class="icon-pin font-green"></i>
					<span class="caption-subject bold uppercase"> Management</span>
				</div>
				<div class="actions">
					<a id="control_add" class="btn btn-circle btn-icon-only blue" href="javascript:;" title="Create">
					<i class="fa fa-plus"></i>
					</a>
					<a id="control_edit" class="btn btn-circle btn-icon-only green" href="javascript:;" title="Edit">
					<i class="icon-wrench"></i>
					</a>
					<a id="control_delete" class="btn btn-circle btn-icon-only red" href="javascript:;" title="Delete">
					<i class="icon-trash"></i>
					</a>
					<a id="control_search"class="btn btn-circle btn-icon-only blue-madison" href="javascript:;" title="Search">
					<i class="fa fa-search"></i>
					</a>
					<a id="control_reset" class="btn btn-circle btn-icon-only blue-hoki" href="javascript:;" title="Reset">
					<i class="fa fa-retweet"></i>
					</a>
					<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title="">
					</a>
				</div>
			</div>
			<div class="portlet-body form">
				<form role="form" id="control_form">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group form-md-line-input has-success">
								<div class="input-icon right">
									<input type="text" class="form-control search" id="customer_name" placeholder="Input customer name...">
									<label for="form_control_1">Customer name</label>
									<span class="help-block">Please input...</span>
									<i class="fa fa-ellipsis-h"></i>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group form-md-line-input has-success">
								<div class="input-icon right">
									<input type="text" class="form-control search" id="address" placeholder="Input address...">
									<label for="form_control_1">Address</label>
									<span class="help-block">Please input...</span>
									<i class="fa fa-ellipsis-h"></i>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group form-md-line-input has-success">
								<div class="input-icon right">
									<input type="text" class="form-control search" id="phone" placeholder="Input phone...">
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
		<div class="portlet light bordered" id="row_ui_summary">
			<div class="portlet-title">
				<div class="caption font-green">
					<i class="icon-pin font-green"></i>
					<span class="caption-subject bold uppercase"> Summary</span>
				</div>
				<div class="actions">
					<a id="control_export" class="btn btn-circle btn-icon-only blue" href="javascript:;" title="Export">
					<i class="icon-cloud-upload"></i>
					</a>
					<a id="control_reload" class="btn btn-circle btn-icon-only blue-hoki" href="javascript:;" title="Reload">
					<i class="fa fa-refresh"></i>
					</a>
					<a class="btn btn-circle btn-icon-only btn-default fullscreen" href="javascript:;" data-original-title="" title="">
					</a>
				</div>
			</div>
			<div class="portlet-body form">
				<form role="form">
					<div class="row">
						<div class="col-md-12">
							<div class="table-responsive list-container" id="control_container">
							
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