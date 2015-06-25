<!-- layout se duoc dua du lieu vao -->
<?php $view->extend('GcsAdminBundle::admin.layout.html.php') ?>
<!--  Title c?a Page -->
<?php $view['slots']->start('title') ?>
    <?php echo $GLOBALS['prefixTitle']; ?> - Change offset for location time
<?php $view['slots']->stop() ?>
<?php $view['slots']->start('header') ?>

<small></small>
<?php $view['slots']->stop() ?>
<!-- JS can them vao -->
<?php $view['slots']->start('js') ?>

<!--<script src="/web/admin/assets/plugins/multipleselect/jquery.multiple.select.insert.js"></script>-->
<script src="/web/admin/assets/plugins/multipleselect/jquery.multiple.select.lithium.js"></script>

<script type="text/javascript" src="/web/admin/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="/web/admin/assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js"></script>
<script type="text/javascript" src="/web/admin/assets/plugins/clockface/js/clockface.js"></script>
<script type="text/javascript" src="/web/admin/assets/plugins/bootstrap-daterangepicker/moment.min.js"></script>
<script type="text/javascript" src="/web/admin/assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="/web/admin/assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
<script type="text/javascript" src="/web/admin/assets/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="/web/admin/assets/scripts/custom/components-pickers.js"></script>

<script src="/web/admin/assets/plugins/multipleselect/optimize.js"></script>

<script>
	ComponentsPickers.init();
	var isadmin = '<?=$isadmin?>';
	var infoCusLoc =  parseJson('<?=$infoCusLoc?>');
	var gridView = 'listData';
	var formView = 'formSearch';
	Optimize.initFormControl();
	
	function parseJson(strParse){
		var objectParser = {};
		try{
			objectParser = JSON.parse(strParse);
		} catch(exx){
			console.log(exx);
			objectParser = {};
		}
		return objectParser;
	}
	function getValueInObject(objectTmp, valueIdTmp){
		var valueTmp = '';
		try{
			valueTmp = objectTmp[valueIdTmp];
		} catch(exx){
			valueTmp = '';
		}
		return valueTmp;
	}
	function setLocationByCus(){
		var listCustomer = $('#customer').multipleSelect('getSelects');
		if(listCustomer == "''" || listCustomer == '' || typeof listCustomer == 'object'){
			$('#customer').multipleSelect('checkAll');
			listCustomer = $('#customer').multipleSelect('getSelects');
			$('#customer').multipleSelect('uncheckAll');
		}
		listCustomer = listCustomer.replace(/'/g,'');
		customerClickOption(listCustomer);
	}
	function customerClickOption(listCustomer){
		if(listCustomer == ""){
			$('#customer').multipleSelect('checkAll');
			listCustomer = $('#customer').multipleSelect('getSelects');
			$('#customer').multipleSelect('uncheckAll');
			if(typeof listCustomer == 'object'){
				listCustomer = '';
			}
			listCustomer = listCustomer.replace(/'/g,'');
		}
		if(listCustomer == ''){
			var p = infoCusLoc;
			for (var key in p) {
				if (p.hasOwnProperty(key)) {
					listCustomer += (listCustomer == '' ? '' : ',') + key;
				}
			}
		}
		var arrListCusTmp = listCustomer.split(',');
		var strLocationTmp = '';
		for(var i = 0; i < arrListCusTmp.length; i++){
			var customerIdTmp = arrListCusTmp[i];
			strLocationTmp += getValueInObject(infoCusLoc, customerIdTmp);
		}
		//console.log(strLocationTmp);
		$('#location_id').html(strLocationTmp);
		$("#location_id").multipleSelect({
			filter: true,
			placeholder:'Choose location',
			allSelected:"dashSelectAll_location"
		});
		$('#location_id').multipleSelect('uncheckAll');
		//$('.option').css({'padding-left': '20px'});
	}
	function checkEmpty(){
		var message = '';
		$(".search").each(function(){
			var name = $(this).attr('id');
			var val = $(this).val();
			var msg = $(this).attr('msg');
			if(val.trim() == '' && msg != undefined && name != 'vendor_id'){
				message = msg; return false;
			}
			if(name == 'vendor_id'){
				if(val.trim() == ''){
					message = 'Please choose one Manufacture!'; return false;
				}else {
					var c = val.split(',');	
					if(c.length > 1){
						message = 'Please choose only one Manufacture!'; return false;
					}
				}
			}
		});
		return message;
	}
	function paginationAjax(div, page){
		if(div == ''){
			div = 'listData';
		}
		Optimize.getDataWithAjax(page);
	}
	function showMessage(title, content){
		$.msgBox({
			title: title,
			content: content,
			type: "alert",
			buttons: [{ value: "OK"}],
			success: function (result) {
			}
		});  
	}
	function save(id, dataPost){
		var el = jQuery("#listData");
		var elFS = jQuery("#formSearch");
		App.blockUI({target: el, iconOnly: true});
        App.blockUI({target: elFS, iconOnly: false});
		$.ajax({
			type: "POST",
			url: 'save',
			data: {
				id:id, dataPost:dataPost
			},
			success: function(data) {
				App.initAjax();
                App.unblockUI(el);
                App.unblockUI(elFS);
				if(data == 1){
					showMessage('Message', 'Saved successfully!');
					paginationAjax('', 1);
				} else if(data == -1) {
					showMessage('Message', 'This name already exists!');
				} else {
					showMessage('Message', 'Save error!');
				}
			},
			error: function(){
				App.initAjax();
                App.unblockUI(el);
                App.unblockUI(elFS);
			}
		});
	}

	$('#search').click(function(){
		paginationAjax('', 1);
	});
	$('#reset').click(function(){
		Optimize.resetFormControl();
	});
	$('#add').click(function(){
		var checker = Optimize.checkEmptyFormControl();
		if(typeof checker == 'object'){
			var dataPost = JSON.stringify(checker); 
			Optimize.saveDataWithAjax('', dataPost);
		} else {
			$.msgBox({
				title: 'Message',
				content: checker,
				type: "error",
				buttons: [{ value: "OK"}],
				success: function (result) {
					
				}
			}); 
		}
	});
	$("#edit").click(function(){
		var idEdit = $('#id_edit').val();
		if(idEdit == ''){
			$.msgBox({
				title: 'Message',
				content: 'Please click one row to Edit!',
				type: "error",
				buttons: [{ value: "OK"}],
				success: function (result) {
					
				}
			}); 
			return false;
		}
		var checker = Optimize.checkEmptyFormControl();
		if(typeof checker == 'object'){
			var dataPost = JSON.stringify(checker); 
			Optimize.saveDataWithAjax(idEdit, dataPost);
		} else {
			$.msgBox({
				title: 'Message',
				content: checker,
				type: "error",
				buttons: [{ value: "OK"}],
				success: function (result) {
					
				}
			}); 
		}
	});
	$("#delete").click(function(){
		var idList = Optimize.getIdListForDelete();
		if(idList == ''){
			$.msgBox({
				title: 'Message',
				content: 'Please check the Checkbox to delete!',
				type: "error",
				buttons: [{ value: "OK"}],
				success: function (result) {
					
				}
			}); 
		} else {
			$.msgBox({
				title: 'Message',
				content: 'Are you sure you want to delete this?',
				type: "alert",
				buttons: [{ value: "Yes"},{ value: "No"}],
				success: function (result) {
					if(result == 'Yes'){
						Optimize.deleteDataWithAjax(idList);
					}
				}
			}); 
		}
		return;
	});
	paginationAjax('', 1);
</script>
<?php $view['slots']->stop() ?>

<!-- CSS can them vao -->
<?php $view['slots']->start('css') ?>

<link rel="stylesheet" type="text/css" href="/web/admin/css/jquery-ui-1.8.22.custom.css"/>

<link rel="stylesheet" type="text/css" href="/web/admin/assets/plugins/bootstrap-datepicker/css/datepicker.css"/>
<link rel="stylesheet" type="text/css" href="/web/admin/assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css"/>
<link rel="stylesheet" type="text/css" href="/web/admin/assets/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css"/>
<link rel="stylesheet" type="text/css" href="/web/admin/assets/plugins/bootstrap-datetimepicker/css/datetimepicker.css"/>
<link rel="stylesheet" type="text/css" href="/web/admin/assets/css/report.css"/>
<link rel="stylesheet" type="text/css" href="/web/admin/assets/plugins/multipleselect/multiple-select.css"/>
<style>
	.ms-drop input[type="checkbox"] {
		vertical-align: top;
	}
	#btnContainer{
		float: right;
	}
	.ms-parent {
		background: #fff;
		color: #000;
	}
	::-webkit-input-placeholder {
	   font-style: italic;
	}

	:-moz-placeholder { /* Firefox 18- */
	   font-style: italic;
	}

	::-moz-placeholder {  /* Firefox 19+ */
	   font-style: italic; 
	}

	:-ms-input-placeholder {  
	   font-style: italic;  
	}
	#formSearch label {
		padding-right: 0;
		text-align: left;
		white-space: pre;
		word-wrap: normal;
	}
</style>
<?php $view['slots']->stop() ?>

<!-- Main Content Here -->
<div id="dialog"></div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-reorder"></i> Timezone for location
                </div>
                <div class="tools">
                    <a href="" class="collapse">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config">
                    </a>
                    <a href="" class="reload">
                    </a>
                    <a href="" class="remove">
                    </a>
                </div>
            </div>
            <div class="portlet-body form">
                <form id="formSearch" action="<?php echo $view['router']->generate('gcs_admin_machine'); ?>" method="POST" class="form-horizontal" role='form'>
                    <div class="form-body">
					<br/>
						<!--S row-->
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label col-md-4">Customer</label>
									<div class="col-md-8">
										<select optsl="multiple" optslClick="setLocationByCus" optslCheckAllForSearch="true" placeholder="Choose customer" name="customer" id="customer" class="search">
											<?php foreach($customerList as $item):?>
												<option value="<?php echo $item['customer_id']?>"><?php echo $item['customer_name']?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label col-md-4">Location</label>
									<div class="col-md-8">
										<select msgempty="Please choose location" optsl="single" placeholder="Choose location" name="location_id" id="location_id" class="search">
											<?php foreach($locationList as $item):?>
												<option value="<?php echo $item['location_id']?>"><?php echo $item['location_name'] . ($isadmin ? ' - ' . $item['customer_name'] : '')?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label col-md-4">Timezone</label>
									<div class="col-md-8">
										<select msgempty="Please choose timezone" optsl="single" placeholder="Choose timezone" name="time_zone" id="time_zone" class="search">
											<?php foreach($timeZoneList as $item):?>
												<option value="<?=$item?>"><?=$item?></option>
											<?php endforeach;?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label col-md-4">Apply date</label>
									<div class="col-md-8">
										<div data-date-format="dd-M-yyyy" class="date date-picker">
                                        <input msgempty="Please choose apply date" type="text" class="search form-control " name="apply_date" id="apply_date" placeholder="Select a value..." value="" />
                                        <span class="input-group-btn">
                                        </span>
                                    </div>
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label col-md-4">Apply time</label>
									<div class="col-md-8">
										<div class="input-group">
											<input msgempty="Please choose apply time" timepicker="true" name="apply_time" id="apply_time" type="text" class="form-control timepicker timepicker-24 search">
											<span class="input-group-btn">
											<button class="btn default" type="button"><i class="fa fa-clock-o"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="row" style="margin-bottom: -17px">
								<div style="float:right; margin-top:0px; margin-right:20px;" class="portlet-body form">
								
								<?php if(isset($right['add'])):?>
									<a  href="javascript:void(0);" id="add" title ="Add" machine_id="">        
										<img alt="Add" style="height:33px;margin-left:10px;" src="/web/img/ico-add.png">
									</a>
								<?php endif;?>
								
								<?php if(isset($right['edit'])):?>
									<a  href="javascript:void(0);" id="edit" title ="Update" machine_id="">        
										<img alt="Update" style="height:33px;margin-left:10px;" src="/web/img/ico-edit.png">
									</a>
									<input id="id_edit" type="hidden" value="" />
								<?php endif;?>
								<?php if(isset($right['delete'])):?>
									<a  href="javascript:void(0);" id="delete" title ="Delete" machine_id="">        
										<img alt="Delete" style="height:33px;margin-left:10px;" src="/web/img/ico-delete.png">
									</a>
								<?php endif;?>
									<a  title="Reset" href="javascript:void(0);" id="reset">
										<img alt="reset" style="height:33px;  margin-left:10px;" src="/web/img/ico-reset-credit.png">
									</a>
									<a  title="Search" href="javascript:void(0);" id="search">
										<img alt="search" style="height:33px;margin-left:10px;" src="/web/img/ico-search.png">
									</a>
								</div>
							</div>
						</div>
						<!--E row-->
						<!--S row-->
						<!--E row-->
						<!--S row-->
                         
						<!--E row-->
                    </div>
                </form>
            </div>
        </div>
    </div>
	
	<!--S list data-->
	<div class="col-md-12"  style="margin-top: 15px">
		<div id="listData">

		</div>
	</div>
	<!--E list data-->
</div>
