Optimize = {
	initFormControl: function(){
		$(".search").each(function(){
			var name = $(this).attr('id');
			var val = $(this).val();
			var optsl = $(this).attr('optsl');
			if(optsl != undefined){
				var optslClick = $(this).attr('optslClick');
				var configSelect = {};
				configSelect[optsl] = true;
				configSelect['filter'] = true;
				configSelect['placeholder'] = $(this).attr('placeholder');
				if(optslClick != undefined){
					configSelect['onClick'] = eval(optslClick);
				}
				$('#'+name).multipleSelect(configSelect);
				$('#'+name).multipleSelect('uncheckAll');
				if(configSelect.onClick != undefined){
					$( "input[name*='selectAll"+name+"']" ).click(function(){ 
						var func = eval(optslClick); if(typeof func == 'function'){ func(); }
					});
				}
				$('#'+name).parent().find('.option').css({'padding-left': '20px'});
			}
		});
		$('.multiple').css({'min-width' : '100%', 'width' : 'auto'});
		
		$("#"+gridView+" tr td").live('click',function(){
			if(
				$(this).parent().parent().find('.edit').length < 1 || 
				$(this).find('.check').length > 0 || 
				$(this).find('.delete').length > 0
			){
				console.log('--prevent click--');
				if($(this).find('.check').length > 0){
					if($('.check').parent().find($( "input[type=checkbox]:checked" )).length == $('.check').length){
						$('#checkAll').attr("checked","checked");
					} else {
						$('#checkAll').removeAttr("checked");
					}
				}
			} else {
				var objTr = $(this).parent().find('.edit');
				var id = objTr.attr('id');
				var datas = objTr.attr('datas');
				var p = Optimize.parseJson(datas);
				for (var key in p) {
					if (p.hasOwnProperty(key)) {
						var valTmp = p[key];
						var objTmp = $('#'+key);
						var optsl = objTmp.attr('optsl');
						var opttimepicker = objTmp.attr('timepicker');
						if(optsl != undefined){
							valTmp = valTmp.toString();
							if(valTmp != ''){
								$('#'+key).multipleSelect('setSelects', valTmp.split(','));
							}
							var optslClick = objTmp.attr('optslClick');
							if(optslClick != undefined){
								var func = eval(optslClick);
								if(typeof func == 'function'){
									func();
								}
							}
						} else if(opttimepicker != undefined){
							$('#'+key).timepicker('setTime', valTmp);
						} else {
							$('#'+key).val(valTmp);
						}
					}
				}
				if($('body').scrollTop() > 0){
					$('body, html').animate({scrollTop:0}, 800);
					console.log('execute scroll...');
				}
			}
		});
		
		//$('.option').css({'padding-left': '20px'});
		return false;
	},
	getFormControlValue: function(){
		var search = {};
		$(".search").each(function(){
			var name = $(this).attr('id');
			var val = $(this).val();
			var optsl = $(this).attr('optsl');
			var optslCheckAllForSearch = $(this).attr('optslCheckAllForSearch');
			if(optsl != undefined){
				val = $('#'+name).multipleSelect('getSelects');
				if(val == "''" || val == '' || typeof val == 'object'){
					if(optslCheckAllForSearch != undefined){
						$('#'+name).multipleSelect('checkAll');
						val = $('#'+name).multipleSelect('getSelects');
						$('#'+name).multipleSelect('uncheckAll');
					}
				}				
				if(val == "''" || val == '' || typeof val == 'object'){
					val = '';
				}
			}
			if(name != undefined){
				search[name] = val;
			}
		});
		var searchs = JSON.stringify(search);
		return searchs;
	},
	getDataWithAjax: function(page){
		var searchs = '';
		if(cacheSearch == '' || cacheSearch == undefined){
			searchs = cacheSearch = Optimize.getFormControlValue();
		} else {
			searchs = cacheSearch;
		}
		Metronic.blockUI({ target: '#'+rowManagement, boxed: true, message: 'Searching...'});
		Metronic.blockUI({ target: '#'+rowSummary, boxed: true, message: 'Searching...'});
		$.ajax({
			type: "POST",
			url: getListLink,
			data: {
				page:page, searchs:searchs
			},
			success: function(data) {
				$("#"+gridView).html(data);
				Metronic.unblockUI('#'+rowManagement);
				Metronic.unblockUI('#'+rowSummary);
				$("#"+gridView+" tr").css({'cursor':'pointer'});
				$('.delete').each(function(){
					$(this).click(function(){
						var idDel = $(this).attr('idDel');
						bootbox.dialog({
			                message: "Are you sure you want to delete this?",
			                title: "Message",
			                buttons: {
			                  no: {
			                    label: "No",
			                    className: "red",
			                    callback: function() {
			                    	
			                    }
			                  },
			                  yes: {
			                      label: "Yes",
			                      className: "blue",
			                      callback: function() {
			                    	  Optimize.deleteDataWithAjax(idDel);
			                      }
			                    }
			                }
			            });
					});
				});
				$("#"+gridView+" tr td").css({'text-align': 'center'});
				$("#"+gridView+" tr th").css({'text-align': 'center'});
				$('#checkAll').live('click', function(){
					if($(this).attr("checked") == 'checked' || $(this).attr("checked") == true){
						$("#"+gridView).find('input.check').attr("checked","checked");
					} else{
						$("#"+gridView).find('input.check').removeAttr("checked");
					}
				});
			},
			error: function(){
				Metronic.unblockUI('#'+rowManagement);
				Metronic.unblockUI('#'+rowSummary);
				console.log('Error loading...');
			}
		});
		return false;
	},
	deleteDataWithAjax: function(idList){
		Metronic.blockUI({ target: '#'+rowManagement, boxed: true, message: 'Searching...'});
		Metronic.blockUI({ target: '#'+rowSummary, boxed: true, message: 'Searching...'});
		$.ajax({
			type:"POST",
			url: deleteLink,
			data:{idList:idList},
			success:function(data){
				var colorTmp = (data.indexOf('success') >= 0) ? 'blue' : 'red';
				Metronic.unblockUI('#'+rowManagement);
				Metronic.unblockUI('#'+rowSummary);
				bootbox.dialog({
	                message: data,
	                title: "Message",
	                buttons: {
	                  main: {
	                    label: "OK",
	                    className: colorTmp,
	                    callback: function() {
	                    	if(data.indexOf('success') >= 0){
								Optimize.resetFormControl();
								Optimize.getDataWithAjax(1);
							}
	                    }
	                  }
	                }
	            });
			}  
		});
	},
	saveDataWithAjax: function(id, dataPost){
		Metronic.blockUI({ target: '#'+rowManagement, boxed: true, message: 'Searching...'});
		Metronic.blockUI({ target: '#'+rowSummary, boxed: true, message: 'Searching...'});
		$.ajax({
			type: "POST",
			url: saveLink,
			data: {
				id:id, dataPost:dataPost
			},
			success: function(data) {
				var colorTmp = (data.indexOf('success') >= 0) ? 'blue' : 'red';
				Metronic.unblockUI('#'+rowManagement);
				Metronic.unblockUI('#'+rowSummary);
				bootbox.dialog({
	                message: data,
	                title: "Message",
	                buttons: {
	                  main: {
	                    label: "OK",
	                    className: colorTmp,
	                    callback: function() {
	                    	if(data.indexOf('success') >= 0){
								Optimize.resetFormControl();
								Optimize.getDataWithAjax(1);
							}
	                    }
	                  }
	                }
	            });
			},
			error: function(){
				Metronic.unblockUI('#'+rowManagement);
				Metronic.unblockUI('#'+rowSummary);
				console.log('Error saving...');
			}
		});
	},
	getIdListForDelete: function(){
		var idList = '';
		$('#'+gridView+' .check:checked').each(function(){
			id = $(this).val();
			idList += (idList == '' ? '' : ',') + id;
		});
		return idList;
	},
	resetFormControl: function(){
		$(".search").each(function(){
			var name = $(this).attr('id');
			var val = $(this).val();
			var optsl = $(this).attr('optsl');
			var opttimepicker = $(this).attr('timepicker');
			if(optsl != undefined){
				$('#'+name).multipleSelect('uncheckAll');
				var optslClick = $(this).attr('optslClick');
				if(optslClick != undefined){
					var func = eval(optslClick);
					if(typeof func == 'function'){
						func();
					}
				}
			} else if(opttimepicker != undefined){
				$('#'+name).timepicker('setTime', '0:00:00');
				$('#'+name).val('0:00:00');
			} else {
				$('#'+name).val('');
			}
		});
		$('#id_edit').val('');
		cacheSearch = '';
		if(cacheSearch == '' || cacheSearch == undefined){
			Optimize.getDataWithAjax(1);
		}
	},
	checkEmptyFormControl: function(){
		var msg = '';
		var search = {};
		$(".search").each(function(){
			var name = $(this).attr('id');
			var val = $(this).val();
			var optsl = $(this).attr('optsl');
			var msgEmpty = $(this).attr('msgempty');
			var msgChooseOne = $(this).attr('msgchooseone');
			var optslCheckAllForSearch = $(this).attr('optslCheckAllForSearch');
			if(msgEmpty != undefined){
				if(optsl != undefined){
					val = $('#'+name).multipleSelect('getSelects');
					if(val == "''" || val == '' || typeof val == 'object'){
						msg = msgEmpty; return false;
					}
					if(msgChooseOne != undefined){
						var checkOne = val.split(',');
						if(checkOne.length > 1){
							msg = msgChooseOne; return false;
						}
					}
				} else if(val == ''){
					msg = msgEmpty; return false;
				}
			}
			//for return search
			if(optsl != undefined){
				val = $('#'+name).multipleSelect('getSelects');
				if(val == "''" || val == '' || typeof val == 'object'){
					if(optslCheckAllForSearch != undefined){
						$('#'+name).multipleSelect('checkAll');
						val = $('#'+name).multipleSelect('getSelects');
						$('#'+name).multipleSelect('uncheckAll');
					}
				}				
				if(val == "''" || val == '' || typeof val == 'object'){
					val = '';
				}
			}
			if(name != undefined){
				search[name] = val;
			}
		});
		if(msg != ''){
			return msg;
		}
		return search;
	},
	parseJson: function (strParse){
		var objectParser = {};
		try{
			objectParser = JSON.parse(strParse);
		} catch(exx){
			console.log(exx);
			objectParser = {};
		}
		return objectParser;
	}
}