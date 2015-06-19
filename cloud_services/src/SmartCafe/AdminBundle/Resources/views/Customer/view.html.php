<?php $view->extend('AdminBundle::admin.layout.html.php') ?>
<?php $view['slots']->start('js') ?>
<script type="text/javascript" src="/web/js/admin/customer.js" ></script>
<script>
	var getListLink = '<?php echo $view['router']->generate('sc_admin_customer_get_list'); ?>';
	var getListPage = 0;
	Customer.getList(getListLink, getListPage);
</script>
<?php $view['slots']->stop() ?>
<!-- Main Content Here -->
<?php $view['slots']->start('body') ?>
<div class="portlet box green">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-gift"></i>Customer Management
		</div>
		<div class="tools">
			<a href="javascript:;" class="collapse">
			</a>
			<a href="#portlet-config" data-toggle="modal" class="config">
			</a>
			<a href="javascript:;" class="reload">
			</a>
			<a href="javascript:;" class="remove">
			</a>
		</div>
	</div>
	<div class="portlet-body form">
		<!-- BEGIN FORM-->
		<form action="#" class="horizontal-form">
			<div class="form-body">
				<h3 class="form-section">Information</h3>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Name</label>
							<input type="text" id="customer_name" class="form-control obj-used" placeholder="Please input name">
							<span class="help-block">
							This is inline help </span>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Address</label>
							<input type="text" id="address" class="form-control obj-used" placeholder="Please input address">
							<span class="help-block">
							This is inline help </span>
						</div>
					</div>
					<!--/span-->
					<div class="col-md-6">
						<div class="form-group has-error">
							<label class="control-label">Last Name</label>
							<input type="text" id="lastName" class="form-control" placeholder="Lim">
							<span class="help-block">
							This field has error. </span>
						</div>
					</div>
					<!--/span-->
				</div>
				<!--/row-->
			</div>
			<div class="form-actions right">
				<button type="button" class="btn default">Cancel</button>
				<button type="submit" class="btn green"><i class="fa fa-check"></i> Save</button>
			</div>
		</form>
		<!-- END FORM-->
	</div>
</div>
<!-- BEGIN SAMPLE TABLE PORTLET-->
<div class="portlet box green">
	<div class="portlet-title">
		<div class="caption">
			<i class="fa fa-cogs"></i>Customer' s List
		</div>
		<div class="tools">
			<a href="javascript:;" class="collapse">
			</a>
			<a href="#portlet-config" data-toggle="modal" class="config">
			</a>
			<a href="javascript:;" class="reload">
			</a>
			<a href="javascript:;" class="remove">
			</a>
		</div>
	</div>
	<div class="portlet-body">
		<div class="table-responsive list-container">
			
		</div>
	</div>
</div>
<!-- END SAMPLE TABLE PORTLET-->
<?php $view['slots']->stop() ?>