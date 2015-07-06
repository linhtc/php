<?php
namespace SmartCafe\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SmartCafe\AdminBundle\Model\CustomerModel;
use SmartCafe\AdminBundle\Model\BaseModel;

class CoffeeTableOverViewController extends Controller
{
    public function viewAction($action = 'view'){
    	$class = get_class($this);
    	$baseModel = new BaseModel($this->container);
    	$checkPermission = $baseModel->checkPermission($this, $class, $_SERVER['REQUEST_URI'], $action);
    	if($checkPermission instanceof RedirectResponse){
    		return $checkPermission;
    	}
    	#region get permission for page
    	$userProfile = $checkPermission;
    	$permission = isset($userProfile->permission->$class) ? $userProfile->permission->$class : '';
    	$isadmin = isset($userProfile->isadmin) ? $userProfile->isadmin : 0;
    	$themeStyle = isset($userProfile->config->theme_style) ? $userProfile->config->theme_style : 'layout';
    	$themeColor = isset($userProfile->config->theme_color) ? $userProfile->config->theme_color : 'smart_cafe_blue';
    	#end region get permission for page
    	
    	#region get list coffee table
    	$list = $this->getCoffeeTableList(); // print_r($list); exit;
    	#end region get list coffee table
    	
		$data = array(
			'permission' => $permission,
			'isadmin' => $isadmin,
			'themeStyle' => $themeStyle,
			'themeColor' => $themeColor,
			'tableList' => $list
        );
        return $this->render('SmartCafeAdminBundle:CoffeeTableOverView:view.html.php', $data);
    }
    
    public function getCoffeeTableList(){
    	$sql = '
			SELECT 	cf.id coffee_table_id, cf.coffee_table_name, cf.zone_id,  cfo.coffee_menu_id, cfo.quantity,
				cfo.session, cfo.status, cm.coffee_menu_name, cm.coffee_menu_price, cfo.time_ordered
			FROM sc_coffee_table cf LEFT JOIN sc_coffee_table_order cfo
				ON cf.customer_id = cfo.customer_id AND cf.id = cfo.coffee_table_id
			LEFT JOIN sc_coffee_menu cm
				ON cfo.customer_id = cm.customer_id AND cfo.coffee_menu_id = cm.id
			WHERE cfo.active = 1 OR cfo.active IS NULL
    		ORDER BY cf.zone_id, cf.coffee_table_name
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
    			$time_ordered = !empty($item['time_ordered']) ? $item['time_ordered'] : null;
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
    				if(!empty($time_ordered)){
    					$response->$coffee_table_id->time_ordered = strtotime($time_ordered);
    				}
    				$response->$coffee_table_id->ordering = new \stdClass();
    				if(!empty($coffee_menu_id)){
    					$response->$coffee_table_id->ordering->$coffee_menu_id = $ordering;
    				}
    			} else{
    				$response->$coffee_table_id->total += $quantity;
    				if(!empty($time_ordered)){
	    				if(strtotime($time_ordered) < $response->$coffee_table_id->time_ordered){
	    					$response->$coffee_table_id->time_ordered = strtotime($time_ordered);
	    					
	    				}
    				}
    				if(!empty($coffee_menu_id)){
    					$response->$coffee_table_id->ordering->$coffee_menu_id = $ordering;
    				}
    			}
    		}
    	}
    	return $response;
    }
}










