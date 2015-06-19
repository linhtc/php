/**
Demo script to handle the theme demo
**/
var Customer = {
    // Get list
    getList: function (getListLink, getListPage) {
    	if(getListLink == undefined || getListLink < 0){
    		getListLink = 0;
    	}
    	var searchParams = {};
    	$('.obj-used').each(function(){
    		var keyThis = $(this).attr('id');
    		var valueThis = '';
    		if(keyThis != undefined){
    			valueThis = $(this).val();
    			searchParams[keyThis] = valueThis;
    		}
    	});
    	searchParams = JSON.stringify(searchParams);
    	$.ajax({
			type: "POST",
			url: getListLink,
			data: {
				page:getListPage, request: searchParams
			},
			success: function(response) {
				$('.list-container').html(response);
			},
			error: function(){
				console.log('error in process');
			}
		});
    }

};

/*
var Customer = function () {

    // Handle Theme Settings
    var getList = function () {
    	alert('');
    };
    
    return {
        //main function to initiate the theme
        init: function() {
           getList();
        }
    };

}();
*/
