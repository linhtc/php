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
    	$baseModel = new BaseModel($this->container);
    	$checkPermission = $baseModel->checkPermission($this, get_class($this), $_SERVER['REQUEST_URI'], $action);
    	if($checkPermission instanceof RedirectResponse){
    		return $checkPermission;
    	}
		$data = array(
			
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
    	$request = (isset($_POST['request']) ? $_POST['request'] : '');
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
		//$pagination = Paging::getPaginationStringAjax('listData',($page), $total, 10, '', 'getAll', '?page=');
		#end region config and search data
		
		$dataRender = array(
            'list' =>  $datas,
            //'pagination' => $pagination,
			'total' => $total,
			'pos' => $pos,
			'permission' => $permission,
			'isadmin' => $isadmin
        );
		
        return $this->render('SmartCafeAdminBundle:Customer:list.html.php', $dataRender);
    }
}










