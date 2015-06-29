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
			SELECT 	cf.coffee_table_name, cf.zone_id, cfo.coffee_table_id, cfo.quantity,
				cfo.session, cfo.status, cm.coffee_menu_name, cm.coffee_menu_price
			FROM sc_coffee_table cf LEFT JOIN sc_coffee_table_order cfo
				ON cf.customer_id = cfo.customer_id AND cf.id = cfo.coffee_table_id
			LEFT JOIN sc_coffee_menu cm
				ON cfo.customer_id = cm.customer_id AND cfo.coffee_menu_id = cm.id
			WHERE cfo.active = 1 OR cfo.active IS NULL
		';
		$baseModel = new BaseModel($this->container);
		$list = $baseModel->executeQuery($sql);
		print_r($list); exit;
		return $response;
	}
}










