<?php
/** *************************** */
/** Author: Trương Viết Hùng    */
/** Email: hungvimach@gmail.com */  
/** *************************** */ 
namespace Gcs\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Gcs\AdminBundle\Util\RenderMessage;
use Gcs\AdminBundle\Model\MachineModel;
use Gcs\AdminBundle\Model\LocationModel;
use Gcs\DataBundle\Utils\DataUtility;
use Gcs\AdminBundle\Model\BaseModel;
use Gcs\AdminBundle\Util\Paging;

use Gcs\AdminBundle\Model\SoftwareUpdateIcombineModel;

class ChangeLocationTimeController extends Controller
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
		$softwareList = array(); $firmwareList = array();
		
		/** Check permission */
        $session = $this->getRequest()->getSession();
		$login = $session->get('login');
		$class = get_class($this);
		$permissions = $this->checkPermission();
        if($permissions instanceof RedirectResponse){
            return $permissions;
        }
		DataUtility::setEntityManager($this->get('doctrine.orm.entity_manager'));
		
		$baseModel = new BaseModel($this->container);
		$locationList = $baseModel->baseGetLocationList();
		$customerList = $baseModel->baseGetCustomerList();
		$timeZoneList = $baseModel->baseGetTimeZone(); ksort($timeZoneList);
		$timenow =  date('d-M-Y',time());
		$fromDate = $timenow;
		$toDate = $timenow;
		$isadmin = $login['isadmin'];
		
		$infoCusLoc = new \stdClass();
		foreach($locationList as $item){
			$location_id = $item['location_id'];
			$location_name = $item['location_name'];
			$customer_id = $item['customer_id'];
			$customer_name = $item['customer_name'];
			if($isadmin){
				$location_name = $location_name . ' - ' . $customer_name;
			}
			if(!isset($infoCusLoc->$customer_id)){
				$infoCusLoc->$customer_id = '<option value=\"'.$location_id.'\">'.$location_name.'</option>';
			} else {
				$infoCusLoc->$customer_id .= '<option value=\"'.$location_id.'\">'.$location_name.'</option>';
			}
		}
		
		$arrrayRender= array(
			'right' => $login['right'][$class],
			'customerid'	=> $login['customerid'],
			'isadmin' => $isadmin,
			'infoCusLoc' => json_encode($infoCusLoc),
			'customerList' => $customerList,
			'locationList' => $locationList,
			'timeZoneList' => $timeZoneList
		);
		return $this->render('GcsAdminBundle:ChangeLocationTime:view.html.php',$arrrayRender);  
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
		
		//items per page
		$rows = 10;
		//$page = $_POST['page'];
		if(isset($_POST['page'])){
			$page = $_POST['page'];
			$pos = ($page - 1) * $rows + 1;
		} else{
			$page = 1;
			$pos = 1;
		}

		$searchs = $_POST['searchs'];
		$search = json_decode($searchs);
		if(isset($search->customer)){
			unset($search->customer);
		}
		$applyDate = '';
		if(isset($search->apply_date)){
			$applyDate = $search->apply_date;
			unset($search->apply_date);
		}
		$applyTime = '';
		if(isset($search->apply_time)){
			$applyTime = $search->apply_time;
			unset($search->apply_time);
		}
		$timeChange = $applyDate.' '.$applyTime;
		if(empty($timeChange)){
			$timeChange = date('Y-m-d H:i:s');
		}
		//$search->time_change = $timeChange;
		$timeZone = '';
		if(isset($search->time_zone)){
			$timeZone = $search->time_zone;
			unset($search->time_zone);
		}
		if(!empty($timeZone)){
			$utcPlus = strpos($timeZone, '+') != false ? 1 : 0;
			$utcString = str_replace('-', '', str_replace('+', '', str_replace('UTC ', '', $timeZone)));
			$utcNum = (float)str_replace('45', '75', str_replace('30', '5', str_replace(':', '.', $utcString)));
			$search->utc_string = $utcString;
			$search->utc_plus = $utcPlus;
			$search->utc_num = $utcNum;
		}
		$config = new \stdClass();
		$config->page = $page;
		$config->rowPerPage = 10;
		$config->table = 'gcs_location_change_time';
		$config->colAppend = 'o2.location_name, (SELECT id_sync FROM gcs_customer c WHERE c.id_sync = o2.customerid) customer_id';
		$config->tableJoin = ' INNER JOIN gcs_location o2 ON o2.id_sync = o1.location_id';
		$config->delIf = 'deleted';
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
		
        return $this->render('GcsAdminBundle:ChangeLocationTime:list.html.php',$arrrayRender);
	}
	
	public function saveAction(){
		$id = $_POST['id'];
		$searchs = $_POST['dataPost'];
		$search = json_decode($searchs);
		
		if(isset($search->customer)){
			unset($search->customer);
		}
		$applyDate = '';
		if(isset($search->apply_date)){
			$applyDate = $search->apply_date;
			unset($search->apply_date);
		}
		$applyTime = '';
		if(isset($search->apply_time)){
			$applyTime = $search->apply_time;
			unset($search->apply_time);
		}
		$timeChange = $applyDate.' '.$applyTime;
		if(empty($timeChange)){
			$timeChange = date('Y-m-d H:i:s');
		} else {
			$timeChange = date('Y-m-d H:i:s', strtotime($timeChange));
		}
		$search->time_change = $timeChange;
		$timeZone = '';
		if(isset($search->time_zone)){
			$timeZone = $search->time_zone;
			unset($search->time_zone);
		}
		if(!empty($timeZone)){
			$utcPlus = strpos($timeZone, '+') != false ? 1 : 0;
			$utcString = str_replace('-', '', str_replace('+', '', str_replace('UTC ', '', $timeZone)));
			$utcNum = (float)str_replace('45', '75', str_replace('30', '5', str_replace(':', '.', $utcString)));
			$search->utc_string = $utcString;
			$search->utc_plus = $utcPlus;
			$search->utc_num = $utcNum;
		}
		$config = new \stdClass();
		$config->table = 'gcs_location_change_time';
		$config->checkExist = array('location_id' => 1, 'time_change' => 1);
		$config->delIf = 'deleted';
		
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
		$config->table = 'gcs_location_change_time';
		$config->delIf = 'deleted';
		$baseModel = new BaseModel($this->container);
		$response = $baseModel->baseBasicDelete($search, $config);
		return new Response($response);
	}
	public function curlPostData($string, $url, $debug = false){
		#region encode
		$key = 'uY7Gm3EPID8y3cHeJQtZyUS5xKHlVZSu';
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encode = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_ECB, $iv));
		//print_r($encode); exit;
		#end region encode
		
		#region post data
		//$url = 'https://cloud3.greystonedatatech.com/ZyFKGCxsc2h7N8XnCJhf8VLhdazrYgwt6aHN8QAp/public/station';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		//curl_setopt($ch, CURLOPT_USERPWD, "$userName:$password");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $encode);
		$result = curl_exec($ch);
		curl_close($ch);
		if($debug){
			print_r($result); exit;
		}
		#end region post data
		
		#region decode result
		$key = 'uY7Gm3EPID8y3cHeJQtZyUS5xKHlVZSu';
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($result), MCRYPT_MODE_ECB);
		$json = trim($decrypt);
		$resultSet = json_decode($json, true);
		return $resultSet;
		#end region decode result
	}
	public function executeCode($sql){
		$servername = "bef6b79c70211c3276b05deefcaf1947b0df66f5.rackspaceclouddb.com";
		$username = "gcsuser";
		$password = "ITlt$6$4GDS";
		$database = "gcs";
		$con = \mysqli_connect($servername, $username, $password, $database);
		if (\mysqli_connect_errno()){
			return array();
		}
		$resultSet = \mysqli_query($con, $sql); //print_r($resultSet); exit;
		\mysqli_close($con);
		return $resultSet;
	}
	public function viewManualAction(){
		$softwareList = array(); $firmwareList = array();
		$firmwareList['N/A'] = 'N/A';
		$machineList = $this->getMachineByCustomer('');
		
		//print_r($softwareList); exit;
		
		$softwareList = array(); $firmwareList = array(); $firmwareList2 = array();
		$swUpdateModel = new SoftwareUpdateIcombineModel($this->getDoctrine()->getManager(),$this->getRequest());
		
		 /** Check permission */
        $session = $this->getRequest()->getSession();
		$login = $session->get('login');
		$class = get_class($this);
		$permissions = $this->checkPermission();
        if($permissions instanceof RedirectResponse){
            return $permissions;
        }
		DataUtility::setEntityManager($this->get('doctrine.orm.entity_manager'));
		
		$tmp = new MachineModel($this->getDoctrine()->getManager(),$this->getRequest());
		$baseModel = new BaseModel($this->container);
		$locationList = $baseModel->getCurrentLocation();
		$countryList = $tmp->getCountryList();
		//$machineList = $tmp->getMachineList();
		$customerList = $tmp->getCustomerList();
		$stationTypeList = $tmp->getStationType();
		//print_r($stationTypeList); exit;
		$arrrayRender= array(
			'locationList' => $locationList,
			'countryList' => $countryList,
			'machineList' => $machineList,
			'customerList' => $customerList,
			'softwareList' => $softwareList,
			'firmwareList' => $firmwareList,
			'right' => $login['right'][$class],
			'customerid'	=> $login['customerid'],
			'sites' => array(),
			'stationTypeList' => $stationTypeList,
			'firmwareBySoftware' => json_encode($firmwareList2)
		);
		return $this->render('GcsAdminBundle:MachineUpdate:view_manual.html.php',$arrrayRender);
	}
	
}
