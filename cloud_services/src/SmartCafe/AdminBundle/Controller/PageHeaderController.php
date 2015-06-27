<?php
namespace SmartCafe\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SmartCafe\AdminBundle\Model\DashboardModel;
use SmartCafe\AdminBundle\Model\BaseModel;

class PageHeaderController extends Controller
{
    public function viewAction(){
    	#region get permission for page
		$userProfile = $this->get('session')->get('profile');
		$isadmin = isset($userProfile->isadmin) ? $userProfile->isadmin : 0;
		$themeStyle = isset($userProfile->config->theme_style) ? $userProfile->config->theme_style : 'layout';
		$themeColor = isset($userProfile->config->theme_color) ? $userProfile->config->theme_color : 'smart_cafe_blue';
		#end region get permission for page
		$data = array(
			'isadmin' => $isadmin,
			'themeStyle' => $themeStyle,
			'themeColor' => $themeColor
		);
        return $this->render('SmartCafeAdminBundle:PageHeader:view.html.php', $data);
    }
}










