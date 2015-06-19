<?php
namespace SmartCafe\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SmartCafe\AdminBundle\Model\DashboardModel;
use SmartCafe\AdminBundle\Model\BaseModel;

class DashboardController extends Controller
{
    public function viewAction(){
    	$ctrl = $this;
    	$class = get_class($ctrl);
    	$uri = $_SERVER['REQUEST_URI'];
    	$baseModel = new BaseModel($this->container);
    	$checkPermission = $baseModel->checkPermission($ctrl, $class, $uri, 'view');
    	if($checkPermission instanceof RedirectResponse){
    		return $checkPermission;
    	}
		$data = array(
			
        );
        return $this->render('SmartCafeAdminBundle:Dashboard:view.html.php', $data);
    }
}










