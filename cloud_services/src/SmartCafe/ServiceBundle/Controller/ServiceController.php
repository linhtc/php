<?php
namespace SmartCafe\ServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SmartCafe\ServiceBundle\Model\BaseModel;

class ServiceController extends Controller
{
    public function switchAction(){
		#region get request
		$request = @file_get_contents('php://input');
		#end region get request
		
		#region decrypt
		$key = 'uY7Gm3EPID8y3cHeJQtZyUS5xKHlVZSu';
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($request), MCRYPT_MODE_ECB);
		$json = trim($decrypt);
		$request = json_decode($json);
		$command = isset($request->command) ? $request->command : 0;
		#end region decrypt
		
		$response = new \stdClass();
		switch($command){
			case 1: { //get cf table
				$response->command = $command;
				$response->response = $this->coffeeTableListAction($request);
				break;
			}
			case 2:{ //
				$response->command = $command;
				break;
			}
			default:{
				$response->command = $command;
				$response->message = 'ERROR COMMAND';
			}
		}
		$encode = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, json_encode($response), MCRYPT_MODE_ECB, $iv));
		return new Response($encode);
    }
	function coffeeTableListAction($request){
		#region get permission for page
		$session = $this->getRequest()->getSession();
		$userProfile = $session->get('profile');
		//$permission = isset($userProfile->permission->$class) ? $userProfile->permission->$class : '';
		//$isadmin = isset($userProfile->isadmin) ? $userProfile->isadmin : 0;
		#end region get permission for page
		
		$sql = '
			SELECT 	cf.id coffee_table_id, cf.coffee_table_name, cf.zone_id,  cfo.coffee_menu_id, cfo.quantity,
				cfo.session, cfo.status, cm.coffee_menu_name, cm.coffee_menu_price
			FROM sc_coffee_table cf LEFT JOIN sc_coffee_table_order cfo
				ON cf.customer_id = cfo.customer_id AND cf.id = cfo.coffee_table_id
			LEFT JOIN sc_coffee_menu cm
				ON cfo.customer_id = cm.customer_id AND cfo.coffee_menu_id = cm.id
			WHERE cfo.active = 1 OR cfo.active IS NULL
		';
		$baseModel = new BaseModel($this->container);
		$list = $baseModel->executeQuery($sql);
		
		$response = new \stdClass();
		if(count($list) > 0){
			foreach($list as $item){
				$coffee_table_id = $item['coffee_table_id'];
				$coffee_table_name = $item['coffee_table_name'];
				$zone_id = $item['zone_id'];
				$coffee_menu_id = !empty($item['coffee_menu_id']) ? $item['coffee_menu_id'] : null;
				$coffee_menu_name = !empty($item['coffee_menu_name']) ? $item['coffee_menu_name'] : null;
				$coffee_menu_price = !empty($item['coffee_menu_price']) ? $item['coffee_menu_price'] : null;
				$status = !empty($item['status']) ? $item['status'] : null;
				$quantity = !empty($item['quantity']) ? $item['quantity'] : 0;
				$session_order = !empty($item['session']) ? $item['session'] : null;
				$ordering = new \stdClass();
				$ordering->quantity = $quantity;
				$ordering->coffee_menu_id = $coffee_menu_id;
				$ordering->coffee_menu_name = $coffee_menu_name;
				$ordering->coffee_menu_price = $coffee_menu_price;
				if(!isset($response->$coffee_table_id)){
					$response->$coffee_table_id = new \stdClass();
					$response->$coffee_table_id->coffee_table_name = $coffee_table_name;
					$response->$coffee_table_id->status = $status;
					$response->$coffee_table_id->session = $session_order;
					$response->$coffee_table_id->total = $quantity;
					$response->$coffee_table_id->ordering = new \stdClass();
					if(!empty($coffee_menu_id)){
						$response->$coffee_table_id->ordering->$coffee_menu_id = $ordering;
					}
				} else{
					$response->$coffee_table_id->total += $quantity;
					if(!empty($coffee_menu_id)){
						$response->$coffee_table_id->ordering->$coffee_menu_id = $ordering;
					}
				}
			}
		}
		return $response;
	}
}










