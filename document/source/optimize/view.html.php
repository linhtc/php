<!-- layout se duoc dua du lieu vao -->
<?php $view->extend('GcsAdminBundle::admin.layout.html.php') ?>
<!--  Title c?a Page -->
<?php $view['slots']->start('title') ?>
    <?php echo $GLOBALS['prefixTitle']; ?> - Program
<?php $view['slots']->stop() ?>
<?php $view['slots']->start('header') ?>

<small></small>
<?php $view['slots']->stop() ?>
<!-- JS can them vao -->
<?php $view['slots']->start('js') ?>
<!--
<script src="/web/admin/assets/plugins/multipleselect/jquery.multiple.select.insert.js"></script>
-->
<script src="/web/admin/assets/plugins/multipleselect/jquery.multiple.select.lithium.js"></script>
<script src="/web/admin/assets/plugins/multipleselect/optimize.js"></script>
<script>
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
	function paginationAjax(div, page){
		if(div == ''){
			div = 'listData';
		}
		Optimize.getDataWithAjax(page);
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
	paginationAjax(gridView, 1);
</script>
<?php $view['slots']->stop() ?>

<!-- CSS can them vao -->
<?php $view['slots']->start('css') ?>

<link rel="stylesheet" type="text/css" href="/web/admin/assets/plugins/multipleselect/multiple-select.css"/>
<link rel="stylesheet" type="text/css" href="/web/admin/css/jquery-ui-1.8.22.custom.css"/>

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
	.timeContainer {
		float: left;
		margin-left: -1px;
		width: 95px;
	}
	.timeContainer .ms-drop ul {
		min-width: 5px;
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
                    <i class="fa fa-reorder"></i> Program Management
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
                <form id="formSearch" action="<?php echo $view['router']->generate('gcs_admin_location'); ?>" method="POST" class="form-horizontal" role='form'>
                    <div class="form-body">
					<br/>
						<!--S row-->
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-4">Name</label>
									<div class="col-md-8">
										<input placeholder="" type="text" name="site_name" id="site_name" class="form-control search" />
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="control-label col-md-4">Customer</label>
									<div class="col-md-8">
										<select msgchooseone="Please choose one customer" msgempty="Please choose customer" optsl="multiple" class="form-control2 search" name="customerid" id="customerid" >
											<?php foreach($customerList as $customer){ ?>
											<option value="<?=$customer['id_type']?>"><?=$customer['name']?></option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<!--S row-->
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
