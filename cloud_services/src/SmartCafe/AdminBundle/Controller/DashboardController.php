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
        return $this->render('SmartCafeAdminBundle:Dashboard:view.html.php', $data);
    }
}










