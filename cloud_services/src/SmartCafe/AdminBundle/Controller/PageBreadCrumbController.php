<?php
namespace SmartCafe\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SmartCafe\AdminBundle\Model\DashboardModel;
use SmartCafe\AdminBundle\Model\BaseModel;

class PageBreadCrumbController extends Controller
{
    public function viewAction($routeName){
		$sessionLogin = $this->get('session')->get('profile');
		$uriServer = $_SERVER['REQUEST_URI'];
		$uriMap = isset($sessionLogin->uri_map) ? $sessionLogin->uri_map : '';
		//print_r($uriMap); exit;
		$breadCrumb = isset($uriMap->$uriServer) ? $uriMap->$uriServer : '';
    	$data = array('breadCrumb' => $breadCrumb);
        return $this->render('SmartCafeAdminBundle:PageBreadCrumb:view.html.php', $data);
    }
}










