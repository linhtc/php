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
		$breadCrumb = isset($uriMap->$uriServer->breadcrumb) ? $uriMap->$uriServer->breadcrumb : '';
		$title = isset($uriMap->$uriServer->title) ? $uriMap->$uriServer->title : '';
    	$data = array('breadCrumb' => $breadCrumb, 'title' => $title);
        return $this->render('SmartCafeAdminBundle:PageBreadCrumb:view.html.php', $data);
    }
}