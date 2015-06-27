<?php if($themeStyle == 'layout'){ ?>
<div class="page-bar">
	<ul class="page-breadcrumb">
		<?php echo $breadCrumb; ?>
	</ul>
	<div class="page-toolbar">
		<div id="dashboard-report-range" class="pull-right tooltips btn btn-sm btn-default" data-container="body" data-placement="bottom" data-original-title="Change dashboard date range">
			<i class="icon-calendar"></i>&nbsp; <span class="thin uppercase visible-lg-inline-block"></span>&nbsp; <i class="fa fa-angle-down"></i>
		</div>
	</div>
</div>
<h3 class="page-title">
<?php echo $title; ?> <!-- <small>reports & statistics</small> -->
</h3>
<?php } else { ?>
<ul class="page-breadcrumb breadcrumb">
	<?php echo str_replace('fa-angle-right', 'fa-circle', $breadCrumb); ?>
</ul>
<?php } ?>