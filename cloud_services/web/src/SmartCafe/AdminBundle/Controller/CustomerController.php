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
        return $this->render('AdminBundle:Customer:view.html.php', $data);
    }
    public function getListAction(){
    	$request = (isset($_POST['request']) ? $_POST['request'] : '');
    	$page = (isset($_POST['page']) ? $_POST['page'] : '');
    	return $this->render('SmartCafeAdminBundle:Customer:list.html.php');
    }
}










