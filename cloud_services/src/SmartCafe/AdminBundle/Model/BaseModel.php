<?php
namespace SmartCafe\AdminBundle\Model;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class BaseModel{
    private $objConnection = null;
    private $objSession = null;
    private $objRequest = null;
    private $objRouter = null;
    
    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->objConnection = $this->container->get('doctrine.orm.entity_manager');
        $this->objRequest = $this->container->get('request_stack')->getCurrentRequest();
        $this->objRouter  = $this->container->get('router');
        $this->objSession = $this->objRequest->getSession();
    }
    public function checkPermission($ctrl, $class, $uri, $action = 'view'){
    	$sessionLogin = $this->objSession->get('profile');
    	if(is_object($sessionLogin)){
    		$pattern = $class.'___'.$uri;
    		if(!isset($sessionLogin->username)){
    			return $ctrl->redirect($ctrl->generateUrl('sc_login'));
    		} elseif(!isset($sessionLogin->permission->$pattern->$action) && !$sessionLogin->admin){
    			return $ctrl->redirect($ctrl->generateUrl('sc_permission_denied'));
    		} else {
    			return $class;
    		}
    	} else {
    		return $ctrl->redirect($ctrl->generateUrl('sc_login'));
    	}
    	
    }
    public function getMenuList(){
		$cn = $this->objConnection->getConnection();
		$sql = " SELECT * FROM sc_menu WHERE deleted = 0;";
		$list = $cn->fetchAll($sql);
		$cn->close();
		return $list;
	}
}