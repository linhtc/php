<?php
namespace SmartCafe\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SmartCafe\AdminBundle\Model\CustomerModel;
use SmartCafe\AdminBundle\Model\BaseModel;

class ThemeController extends Controller
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
        return $this->render('SmartCafeAdminBundle:Theme:view.html.php', $data);
    }
    public function getListAction(){
    	$request = (isset($_POST['request']) ? $_POST['request'] : '');
    	$page = (isset($_POST['page']) ? $_POST['page'] : '');
    	return $this->render('SmartCafeAdminBundle:Theme:list.html.php');
    }
}










