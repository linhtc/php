<?php
namespace SmartCafe\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use SmartCafe\AdminBundle\Model\LoginModel;
use Symfony\Component\HttpFoundation\Symfony\Component\HttpFoundation;

class LoginController extends Controller
{
    public function viewAction(){
		$data = array();
        return $this->render('SmartCafeAdminBundle:Login:view.html.php', $data);
    }
    public function authenticationAction(){
    	//$datas = @file_get_contents('php://input');
    	$username = isset($_POST['username']) ? $_POST['username'] : '';
    	$password = isset($_POST['password']) ? $_POST['password'] : '';
    	$message = '';
    	if(!empty($username) && !empty($password)){
    		$loginModel = new LoginModel($this->container, $this);
    		$checker = $loginModel->checkAuthentication($username, $password);
    		if($checker){
    			return $this->redirect($this->generateUrl('sc_admin_dashboard'));
    		} else {
    			$message = 'Username or password incorect.';
    			$data = array(
    					'message' => $message
    			);
    			return $this->render('SmartCafeAdminBundle:Login:view.html.php', $data);
    		}
    	} else {
    		$message = 'Enter any username and password.';
    		$data = array(
    			'message' => $message
    		);
    		return $this->render('SmartCafeAdminBundle:Login:view.html.php', $data);
    	}
    	return new Response($message);
    }
    public function logOutAction(){
    	$session = $this->getRequest()->getSession();
    	$session->remove('profile');
    	$session->save();
    	return $this->redirect($this->generateUrl('sc_login'));
    }
    public function lockScreenAction(){
    	$data = array();
    	return $this->render('SmartCafeAdminBundle:Login:lock.screen.html.php', $data);
    }
}










