<?php
namespace SmartCafe\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SmartCafe\AdminBundle\Model\CustomerModel;
use SmartCafe\AdminBundle\Model\BaseModel;

class CustomerController extends Controller
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
		$data = array(
			'permission' => $permission,
			'isadmin' => $isadmin,
			'themeStyle' => $themeStyle,
			'themeColor' => $themeColor
        );
        return $this->render('SmartCafeAdminBundle:Customer:view.html.php', $data);
    }
    public function getListAction(){
		#region check permission
		$class = get_class($this);  
		$baseModel = new BaseModel($this->container);
		$checkPermission = $baseModel->checkPermission($this, $class, $_SERVER['REQUEST_URI'], 'view');
    	if($checkPermission instanceof RedirectResponse){
    		return 'Permission denied!';
    	}
		#end region check permission
		
		#region get request
    	$request = (isset($_POST['searchs']) ? $_POST['searchs'] : '');
    	$page = (isset($_POST['page']) ? $_POST['page'] : '');
		#end region get request
		
		#region get permission for page
		$session = $this->getRequest()->getSession();
		$userProfile = $session->get('profile');
		$permission = isset($userProfile->permission->$class) ? $userProfile->permission->$class : '';
		$isadmin = isset($userProfile->isadmin) ? $userProfile->isadmin : 0;
		#end region get permission for page
		
		#region config and search data
		$rows = 10;
		if(isset($_POST['page'])){
			$page = $_POST['page'];
			$pos = ($page - 1) * $rows + 1;
		} else{
			$page = 1;
			$pos = 1;
		}
		$search = json_decode($request);
		$config = new \stdClass();
		$config->page = $page;
		$config->rowPerPage = 10;
		$config->table = 'sc_customer';
		$config->colAppend = '';
		$config->tableJoin = '';
		$config->delIf = 'deleted';
		$resultSet = $baseModel->baseBasicSearch($search, $config);
		$total = 0;
		if(isset($resultSet->total)){
			$total = $resultSet->total;
		}
		if(isset($resultSet->datas)){
			$datas = $resultSet->datas;
		}
		$pagination = $baseModel->baseGetPageMap('listData', $page, $total, 10, '', 'getAll', '?page=');
		#end region config and search data
		
		$dataRender = array(
            'list' =>  $datas,
            'pagination' => $pagination,
			'total' => $total,
			'pos' => $pos,
			'permission' => $permission,
			'isadmin' => $isadmin
        );
		
        return $this->render('SmartCafeAdminBundle:Customer:list.html.php', $dataRender);
    }
    public function saveAction(){
    	#region get request
    	$id = $_POST['id'];
    	$searchs = $_POST['dataPost'];
    	$search = json_decode($searchs);
    	#end region get request
    	
    	#region check permission
    	$action = empty($id) ? 'add' : 'edit';
    	$class = get_class($this);
    	$baseModel = new BaseModel($this->container);
    	$checkPermission = $baseModel->checkPermission($this, $class, $_SERVER['REQUEST_URI'], $action);
    	if($checkPermission instanceof RedirectResponse){
    		return 'Permission denied!';
    	}
    	#end region check permission
    	
    	#region config and execute
    	$config = new \stdClass();
    	$config->table = 'sc_customer';
    	$config->checkExist = array('customer_name' => 1);
    	$config->delIf = 'deleted';
    	$baseModel = new BaseModel($this->container);
    	if(empty($id)){
    		$response = $baseModel->baseBasicInsert($search, $config);
    	} else{
    		$config->idEdit = $id;
    		$response = $baseModel->baseBasicUpdate($search, $config);
    	}
    	#emd region config and execute
    	
    	return new Response($response);
    }
    public function deleteAction(){
    	$idList = $_POST['idList'];
    	$search = new \stdClass();
    	$search->id = $idList;
    	$config = new \stdClass();
    	$config->table = 'sc_customer';
    	$config->delIf = 'deleted';
    	$baseModel = new BaseModel($this->container);
    	$response = $baseModel->baseBasicDelete($search, $config);
    	return new Response($response);
    }
}










