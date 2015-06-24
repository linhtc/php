<?php
namespace Gcs\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Gcs\AdminBundle\Util\RenderMessage;
use Gcs\AdminBundle\Model\ProgramModel;
use Gcs\DataBundle\Utils\DataUtility;
use Gcs\AdminBundle\Model\BaseModel;
use Gcs\AdminBundle\Util\Paging;


class ProgramController extends Controller
{
    private $_session = null;
    public function checkPermission($action='view'){
		$class = get_class($this);
		$session = $this->getRequest()->getSession();
		$login = $session->get('login');
		if(empty($login['username'])){ //Chưa đăng nhập
			//print_r($this->generateUrl('gcs_login')); exit;
			return $this->redirect($this->generateUrl('gcs_login'));
		}
		else if(!isset($login['right'][$class][$action])){
			return $this->redirect($this->generateUrl('gcs_login'));
		}
		else{
			return $class;
		}
    }
	public function viewAction(){
		//Check permission
        $session = $this->getRequest()->getSession();
		$login = $session->get('login');
		$class = get_class($this);
		$permissions = $this->checkPermission();
        if($permissions instanceof RedirectResponse){
            return $permissions;
        }
		DataUtility::setEntityManager($this->get('doctrine.orm.entity_manager'));
		$customerid = $login['customerid'];
		$tmp = new ProgramModel($this->getDoctrine()->getManager(),$this->getRequest());
		$customerList = $tmp->getCustomerList();
		
		$arrrayRender= array(
			'customerList' => $customerList,
			'right' => $login['right'][$class],
			'customerid' => $customerid
		);
		return $this->render('GcsAdminBundle:Program:view.html.php',$arrrayRender);
	}
	public function getListAction(){
		// Check permission
		$session = $this->getRequest()->getSession();
		$login = $session->get('login');
		$class = get_class($this);
		$permissions = $this->checkPermission();
        if($permissions instanceof RedirectResponse){
            return $permissions;
        }
		$rows = 10;
		if(isset($_POST['page'])){
			$page = $_POST['page'];
			$pos = ($page - 1) * $rows + 1;
		} else{
			$page = 1;
			$pos = 1;
		}

		$searchs = $_POST['searchs'];
		$search = json_decode($searchs);
		$config = new \stdClass();
		$config->page = $page;
		$config->rowPerPage = 10;
		$config->table = 'gcs_site';
		$config->colAppend = '(SELECT c.customer_name FROM gcs_customer c WHERE c.id_sync = o1.customerid) customer_name';
		$config->tableJoin = '';
		$config->delIf = 'isdelete';
		$config->ordering = ' ORDER BY  o1.id desc ';
		$baseModel = new BaseModel($this->container);
		$resultSet = $baseModel->baseBasicSearch($search, $config);
		$resultSet2 = array();
		$dataReturn = array();
		$total = 0;
		if(isset($resultSet->total)){
			$total = $resultSet->total;
		}
		if(isset($resultSet->datas)){
			$datas = $resultSet->datas;
		}
		
		$pagination = Paging::getPaginationStringAjax('listData',($page), $total, 10, '', 'getAll', '?page=');
		$arrrayRender = array(
            'list' =>  $datas,
            'pagination' => $pagination,
			'total' => $total,
			'pos' => $pos,
			'right' => $login['right'][$class],
			'isadmin' => $login['isadmin']
        );
		
        return $this->render('GcsAdminBundle:Program:list.html.php',$arrrayRender);
	}
	public function saveAction(){
		$id = $_POST['id'];
		$searchs = $_POST['dataPost'];
		$search = json_decode($searchs);
		$config = new \stdClass();
		$config->table = 'gcs_site';
		$config->checkExist = array('site_name' => 1, 'customerid' => 1);
		$config->delIf = 'isdelete';
		
		$baseModel = new BaseModel($this->container);
		if(empty($id)){
			$response = $baseModel->baseBasicInsert($search, $config);
		} else{
			$config->idEdit = $id;
			$response = $baseModel->baseBasicUpdate($search, $config);
		}
		return new Response($response);
	}
	public function deleteAction(){
		$idList = $_POST['idList'];
		$search = new \stdClass();
		$search->id = $idList;
		$config = new \stdClass();
		$config->table = 'gcs_site';
		$config->delIf = 'isdelete';
		$baseModel = new BaseModel($this->container);
		$response = $baseModel->baseBasicDelete($search, $config);
		return new Response($response);
	}
}
