<?php
namespace SmartCafe\SmartServiceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SmartCafe\SmartServiceBundle\Model\BaseModel;

class SmartServiceController extends Controller
{
    public function viewAction(){
    	$data = array();
        return $this->render('SmartServiceBundle:SmartService:view.html.php', $data);
    }
}






