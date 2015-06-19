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
    	$data = array();
        return $this->render('SmartCafeAdminBundle:PageBreadCrumb:view.html.php', $data);
    }
}










