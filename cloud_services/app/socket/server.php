<?php
$host = 'localhost'; //host
$port = '9999'; //port
$null = NULL; //null var
$key = 'uY7Gm3EPID8y3cHeJQtZyUS5xKHlVZSu';
$url = 'http://172.16.5.40/smart-cafe/service/';

//Create TCP/IP sream socket
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//reuseable port
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

//bind socket to specified host
socket_bind($socket, 0, $port);

//listen to port
socket_listen($socket);

//create & add listning socket to the list
$clients = array($socket);

//start endless loop, so that our script doesn't stop
while (true) {
	//manage multipal connections
	$changed = $clients;
	//returns the socket resources in $changed array
	socket_select($changed, $null, $null, 0, 10);
	
	//check for new socket
	if (in_array($socket, $changed)) {
		$socket_new = socket_accept($socket); //accpet new socket
		$clients[] = $socket_new; //add socket to client array
		
		$header = socket_read($socket_new, 1024); //read data sent by the socket
		perform_handshaking($header, $socket_new, $host, $port); //perform websocket handshake
		
		socket_getpeername($socket_new, $ip); //get ip address of connected socket
		$response = mask(json_encode(array('command'=>0, 'message'=>$ip.' connected'))); //prepare json data
		send_message($response); //notify all users about new connection
		
		//make room for new socket
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
	}
	//loop through all connected sockets
	foreach ($changed as $changed_socket) {	
		
		//check for any incomming data
		while(socket_recv($changed_socket, $buf, 1024, 0) >= 1){
			$received_text = unmask($buf); //unmask data
			//$client_command = json_decode($received_text);
			$response_text = mask(switch_command($received_text));
			send_message($response_text); //send data
			break 2; //exist this loop
		}
		
		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client
			// remove client for $clients array
			$found_socket = array_search($changed_socket, $clients);
			socket_getpeername($changed_socket, $ip);
			unset($clients[$found_socket]);
			
			//notify all users about disconnected connection
			$response = mask(json_encode(array('command'=>0, 'message'=>$ip.' disconnected')));
			send_message($response);
		}
	}
}
// close the listening socket
socket_close($sock);

//Send message
function send_message($msg){
	global $clients;
	foreach($clients as $changed_socket) {
		@socket_write($changed_socket,$msg,strlen($msg));
	}
	return true;
}

//Unmask incoming framed message
function unmask($text) {
	$length = ord($text[1]) & 127;
	if($length == 126) {
		$masks = substr($text, 4, 4);
		$data = substr($text, 8);
	} elseif($length == 127) {
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	} else {
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i) {
		$text .= $data[$i] ^ $masks[$i%4];
	}
	return $text;
}

//Encode message for transfer to client.
function mask($text){
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);
	if($length <= 125){
		$header = pack('CC', $b1, $length);
	} elseif($length > 125 && $length < 65536){
		$header = pack('CCn', $b1, 126, $length);
	} elseif($length >= 65536){
		$header = pack('CCNN', $b1, 127, $length);
	}
	return $header.$text;
}

//handshake new client.
function perform_handshaking($receved_header,$client_conn, $host, $port){
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);
	foreach($lines as $line){
		$line = chop($line);
		if(preg_match('/\A(\S+): (.*)\z/', $line, $matches)){
			$headers[$matches[1]] = $matches[2];
		}
	}
	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	//hand shaking header
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
	"Upgrade: websocket\r\n" .
	"Connection: Upgrade\r\n" .
	"WebSocket-Origin: $host\r\n" .
	"WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
	"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	socket_write($client_conn,$upgrade,strlen($upgrade));
}

//Post data to service
function curlPost($string = ''){
	$key = 'uY7Gm3EPID8y3cHeJQtZyUS5xKHlVZSu';
	$url = 'http://172.16.5.40/smart-cafe/service/';
	#region encode
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$encode = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_ECB, $iv));
	#end region encode
	
	#region post data
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_USERPWD, "linhtran:linhtran@greystonevn.com");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $encode);
	$result = curl_exec($ch); print_r($result);
	curl_close($ch);
	#end region post data
	
	#region decode result
	$decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($result), MCRYPT_MODE_ECB);
	return trim($decrypt);
	#end region decode result
}

//Switch command from clients
function switch_command($client_command){
	return curlPost($client_command);
}
