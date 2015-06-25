<?php
namespace SmartCafe\AdminBundle\Model;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class LoginModel{
    private $objConnection = null;
    private $objSession = null;
    private $objRequest = null;
    private $objRouter = null;
    private $objCtrl = null;
        
    public function __construct(ContainerInterface $container, $ctrl){
        $this->container = $container;
        $this->objConnection = $this->container->get('doctrine.orm.entity_manager');
        $this->objRequest = $this->container->get('request_stack')->getCurrentRequest();
        $this->objRouter  = $this->container->get('router');
        $this->objSession = $this->objRequest->getSession();
        $this->objCtrl = $ctrl;
    }
    public function checkAuthentication($username, $password){
    	//encrypt password
    	$password = md5(md5(md5($password.$username)));
		$cn = $this->objConnection->getConnection();
		$sql = "
			SELECT 	u.*, g.group_name, g.customer_id, g.permission, g.admin, c.customer_name
			FROM 	sc_user u INNER JOIN sc_group g ON u.group_id = g.id INNER JOIN sc_customer c ON g.customer_id = c.id
			WHERE 	u.username = '".$username."' AND u.password = '".$password."' AND u.deleted = 0
				AND c.deleted = 0 AND g.deleted = 0 AND u.deleted = 0;
		";
		$objUser = $cn->fetchAll($sql);
		if(count($objUser) == 1){
			$session = $this->objSession;
			$profile = new \stdClass();
			foreach($objUser[0] as $iKey=>$iVal){
				$profile->$iKey = $iVal;
			}
			if(isset($profile->permission)){
				$profile->permission = json_decode($profile->permission);
			}
			$permissionList = $profile->permission;
			$permissionRole = new \stdClass();
			$menuRoute = new \stdClass();
			$menuRoot = new \stdClass();
			$menuObj = new \stdClass();
			$menuMap = new \stdClass();
			$uriMap = new \stdClass();
			$uriMapTmp = new \stdClass();
			//get menu list
			$sql = "
				SELECT 	id, menu_name, parent_id, route, url, uri, icon
				FROM 	sc_menu
				WHERE 	deleted = 0
				ORDER BY ordering;		
			";
			$menuList = $cn->fetchAll($sql);
			//config permission and menu
			foreach($menuList as $item){
				$idMenu = $item['id'];
				$parentidMenu = $item['parent_id'];
				$nameMenu = $item['menu_name'];
				$routeMenu = $item['route'];
				$urlMenu = $item['url'];
				$uriMenu = $item['uri'];
				$iconMenu = $item['icon'];
				if(!isset($menuRoute->$idMenu) && !empty($urlMenu) && !empty($uriMenu)){
					$menuRoute->$idMenu = $routeMenu.'___'.$uriMenu;
				}
				if(!empty($uriMenu)){
					if(!isset($uriMapTmp->$idMenu)){
						$uriMapTmp->$idMenu = $uriMenu;
					}	
				}
				if(!isset($menuRoot->$idMenu) && $parentidMenu == 0){
					$menuRoot->$idMenu = $idMenu;
				}
				if(!isset($menuObj->$idMenu)){
					$menuObj->$idMenu = (object)array('parent_id' => $parentidMenu, 'name' => $nameMenu, 'url' => $urlMenu, 'uri' => $uriMenu, 'icon' => $iconMenu);
				}
				if(!isset($menuMap->$parentidMenu)){
					$menuMap->$parentidMenu = (object)array($idMenu => $idMenu);
				} else {
					$menuMap->$parentidMenu->$idMenu = $idMenu;
				}
			}
			$runable = 0;
			$level = 1;
			$break = ($profile->admin) ? count($menuRoot) : 0;
			if(count($permissionList)){
				foreach($permissionList as $idMenu=>$roleMenu){
					$break++;
					if(isset($menuRoute->$idMenu)){
						$ctrlMenu = $menuRoute->$idMenu;
						if(!isset($permissionRole->$ctrlMenu)){
							$permissionRole->$ctrlMenu = $roleMenu;
						}
					}
				}
			}
			$breadCrumb = '
				<li>
					<i class="fa fa-home"></i>
					<a href="'.($this->objCtrl->generateUrl('sc_admin_dashboard')).'">Home</a>
					<i class="fa fa-angle-right"></i>
				</li>
			';
			$menuHtml = '	
			';
			//print_r($menuRoot); echo '<hr />';
			//print_r($menuMap); echo '<hr />';
			//print_r($uriMapTmp); echo '<hr />'; exit;
			foreach($menuRoot as $idMenu){
				if(isset($permissionList->$idMenu->view) || $profile->admin){
					$runable++;
					$objMenu = $menuObj->$idMenu;
					$menuName = $objMenu->name;
					$menuIcon = $objMenu->icon;
					$menuUrl = $objMenu->url;
					if(!empty($menuUrl)){
						$menuUrl = $this->objCtrl->generateUrl($menuUrl);
					} else {
						$menuUrl = 'javascript:;';
					}
					$menuHtml .= '
						<li '.(($runable == $break) ? 'class="last"' : '').' level='.$level.' style="'.((empty($objMenu->url) && !isset($menuMap->$idMenu)) ? 'display:none;' : '').'">
							<a href="'.$menuUrl.'">
							<i class="'.$menuIcon.'"></i>
							<span class="title">'.$menuName.'</span>
							'.(isset($menuMap->$idMenu) ? '<span class="arrow "></span>' : '').'
							</a>	
					';
					$breadCrumbTmp = '
						<li>
							<a href="'.$menuUrl.'">'.$menuName.'</a>'.(isset($menuMap->$idMenu) ? '<i class="fa fa-angle-right"></i>' : '').'
						</li>
					';
					if(isset($uriMapTmp->$idMenu)){
						$uriTmp = $uriMapTmp->$idMenu;
						$uriMap->$uriTmp = new \stdClass();
						$uriMap->$uriTmp->breadcrumb = $breadCrumb.$breadCrumbTmp;
						$uriMap->$uriTmp->title = $menuName;
					}
					if(isset($menuMap->$idMenu)){
						$this->getChildMenu($menuMap, $menuMap->$idMenu, $menuObj, $menuHtml, $permissionList, $profile->admin, $level, $uriMap, $uriMapTmp, $breadCrumb.$breadCrumbTmp);
					}
					$menuHtml .= '</li>';
				}
			}
			$profile->menu = $menuHtml;
			$profile->uri_map = $uriMap;
			$profile->permission = $permissionRole;
			$session->set('profile', $profile);
			$session->save();
			return true;
		}
		$cn->close();
		return false;
	}
	private function getChildMenu($menuMap, $menuChild, $menuObj, &$menuHtml, $permissionList, $admin, $level, &$uriMap, $uriMapTmp, $breadCrumb){
		$level++;
		$menuHtml .= '
			<ul class="sub-menu">
		';
		
		foreach($menuChild as $idMenuChild){
			if(isset($permissionList->$idMenuChild->view) || $admin){
				$objMenu = $menuObj->$idMenuChild;
				$menuName = $objMenu->name;
				$menuIcon = $objMenu->icon;
				$menuUrl = $objMenu->url;
				if(!empty($menuUrl)){
					$menuUrl = $this->objCtrl->generateUrl($menuUrl);
				} else {
					$menuUrl = 'javascript:;';
				}
				$menuHtml .= '
					<li '.(!empty($menuUrl) ? 'class="'.(str_replace('.', '_', str_replace('/', '_', $menuUrl))) : '').'" level='.$level.' style="'.((empty($objMenu->url) && !isset($menuMap->$idMenuChild)) ? 'display:none;' : '').'">
						<a href="'.$menuUrl.'">
						<i class="'.$menuIcon.'"></i>
								'.$menuName.''.(isset($menuMap->$idMenuChild) ? '<span class="arrow "></span>' : '').'</a>
				';
				
				$breadCrumbTmp = '
					<li>
						<a href="'.$menuUrl.'">'.$menuName.'</a>'.(isset($menuMap->$idMenuChild) ? '<i class="fa fa-angle-right"></i>' : '').'
					</li>
				';
				if(isset($uriMapTmp->$idMenuChild)){
					$uriTmp = $uriMapTmp->$idMenuChild;
					$uriMap->$uriTmp = new \stdClass();
					$uriMap->$uriTmp->breadcrumb = $breadCrumb.$breadCrumbTmp;
					$uriMap->$uriTmp->title = $menuName;
				}
				
				if(isset($menuMap->$idMenuChild)){
					$this->getChildMenu($menuMap, $menuMap->$idMenuChild, $menuObj, $menuHtml, $permissionList, $admin, $level, $uriMap, $uriMapTmp, $breadCrumb.$breadCrumbTmp);		
				}
				$menuHtml .= '
					</li>
				';
			}
		}
		$menuHtml .= '
			</ul>
		';
	}
}