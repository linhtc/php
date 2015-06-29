<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8' />
</head>
<body>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

<script language="javascript" type="text/javascript">  
$(document).ready(function(){
	//create a new WebSocket object.
	var wsUri = "ws://172.16.5.99:9000"; 	
	websocket = new WebSocket(wsUri); 
	
	websocket.onopen = function(ev) { // connection is open 
		$('#message_box').append("<div class=\"system_msg\">Connected!</div>"); //notify user
	}

	//#### Message received from server?
	websocket.onmessage = function(ev) {
		console.log('-------------- Server response --------------');
		console.log(ev.data);
		try{
			console.log('-------------- Parse response --------------');
			var response = JSON.parse(ev.data);
			if(response.command == 0){
				console.log(response.message);
				return false;
			}
			if(typeof response == 'object'){
				response = response.response;
			}
			if(typeof response == 'object'){
				for(var key in response){
					if (response.hasOwnProperty(key)) {
						console.log(key);
						console.log(response[key]);
					}
				}
			}
			return false;
		} catch(exx){
			console.log(exx.message);
		}
	};
	
	websocket.onerror	= function(ev){
		console.log(ev);
	}; 
	websocket.onclose 	= function(ev){
		console.log(ev);
	}; 
});
</script>
</body>
</html>