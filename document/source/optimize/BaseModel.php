<?php
namespace Gcs\AdminBundle\Model;

use Doctrine\ORM\EntityManager;
use Gcs\DataBundle\Utils\DataUtility;
use Gcs\AdminBundle\Util\Paging;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Gcs\DataBundle\Lib\ExcelUtility;

class BaseModel extends ContainerAware
{
	private $_limit = 10;
    private $_limitStart = 0;
    private $_adjacents = 1;
    private $_em = null;
    private $_session = null;
    private $_request = null;
    private $_router = null;
    private $limitStart = 0;
	
    public function __construct(ContainerInterface $container){
        $this->container = $container;
        $this->_em = $this->container->get('doctrine.orm.entity_manager');
        $this->_request = $this->container->get('request_stack')->getCurrentRequest();
        $this->_router  = $this->container->get('router');
    }
	public function getCustomer($customerid){
        $cn = $this->_em->getConnection();
		$search = "";
		if(!empty($customerid)){
			$search.= " and c.id = ".$customerid;
		}
		$sql = "
				select c.id, c.customer_name, c.customer_type
				from gcs_customer c
				where c.isdelete = 0
				$search
				";
		return $cn->fetchAll($sql);;
    }
	public function getMachine($customerid){
        $cn = $this->_em->getConnection();
	    $search = "";
		if(!empty($customerid)){
			$search.= " and m.user_id = ".$customerid;
		}
		$sql = "
				select m.machine_sn, m.user_id customerid, m.online_status
				from izap_machine m
				where m.delif = 0
				$search
				";
		return $cn->fetchAll($sql);;
    }
	public function getCountryList(){
		$cn = $this->_em->getConnection();
		$sql = "SELECT iso2, short_name name FROM gcs_country ORDER BY name";
			
        $result = $cn->fetchAll($sql);
        return $result;
    }
	public function getLocation($customerid){			
		$cn = $this->_em->getConnection();
		$search = "";
		if(!empty($customerid)){
			$search = " and l.customerid = $customerid";
		}
		$sql = "
			SELECT l.`id` id, l.location_name name
			FROM gcs_location l
			Where l.deif = 0
			$search
			ORDER BY name
		";
		return $cn->fetchAll($sql);
    }
	public function getCurrentLocation(){
		$cn = $this->_em->getConnection();
		$session = $this->_request->getSession();
		$login = $session->get('login');
		$customerid = $login['customerid'];
		$locationList = $login['location'];
		
		// start filter by customer, location
		$where = '';
		if(!empty($customerid)){
			$where .= " AND L.owner_by = '".$customerid."'";
		}
		
		if(!empty($locationList))
			$where .= " AND L.id IN ($locationList)";
		// end filter

		$sql = "
			SELECT L.`id` location_id, L.location_name
			FROM gcs_location L
			WHERE L.del_if = 0
			$where
		"; 
		//echo $sql . 'sadsa'; exit;
		$location = $this->executeCode($sql);
		return $location;
	}
	public function getCurrentShift(){
		$cn = $this->_em->getConnection();
		$session = $this->_request->getSession();
		$login = $session->get('login');
		$customerid = $login['customerid'];
		$shiftList = $login['shifts'];
		
		$where = '';	
		
		// start filter by customer, shift
		if(!empty($customerid)){
			$where .= " AND S.customerid = '".$customerid."'";
		}
			
		if(!empty($shiftList))
			$where .= " AND S.id IN ($shiftList)";
		// end filter
		
		$sql = "
			SELECT DISTINCT CONCAT_WS('-', S.start, S.end) AS shiftvalue, CONCAT_WS(' - ', S.start, S.end) AS shiftname 
			FROM gcs_shift S
			WHERE 1=1 $where
			ORDER BY S.shift ASC;
		";
		//echo $sql;die;
		$result = $cn->fetchAll($sql);
		return $result;
	}
	public function getCurrentMachine(){
		$em = $this->_em;
        $cn=$em->getConnection();
		$session = $this->_request->getSession();
		$login = $session->get('login');
		$customerid = $login['customerid'];
		$locationList = $login['location'];
		
		// customer = 0 is ADMIN
		
		$where = '';
		if(empty($customerid))
			$where .= " AND (MC.delif = 0 OR MC.delif IS NULL)";
		else
			$where .= " AND MC.user_id = $customerid AND (MC.delif = 0 OR MC.delif IS NULL) ";
		
		if(!empty($locationList))
			$where .= " AND MC.location IN ($locationList)";
		
		$sql = "SELECT MC.machine_sn station_serial, MC.machine_sn, MC.user_id customerid, MC.online_status
				FROM izap_machine MC 
				WHERE 1=1 $where AND MC.machine_sn IS NOT NULL 
				ORDER BY MC.machine_sn";
			
        $result = $cn->fetchAll($sql);
        return $result;
    }
	public function getModelMongo($modelList = '', $keyVal = false, $flagKeyName = false){
		$where = '';
		if(!empty($modelList)){
			$where = " WHERE m.id IN($modelList)";
		}
		$sql = "
			SELECT m.id, m.model_name
			FROM gcs_model m
			$where
		";
		$result = $this->executeCode($sql);
		$resultSet = array();
		if(!$keyVal){
			$i = 0;
			if($result){
				foreach($result as $item){
					$resultSet[$i] = $item;
					$i++;
				}
			}
		}
		else{
			if($result){
				if(!$flagKeyName){
					foreach($result as $item){
						$id = $item['id'];
						$model_name = $item['model_name'];
						if(!isset($resultSet[$id])){
							$resultSet[$id] = $model_name;
						}
					}
				}
				else{
					foreach($result as $item){
						$id = $item['id'];
						$model_name = $item['model_name'];
						if(!isset($resultSet[$model_name])){
							$resultSet[$model_name] = $id;
						}
					}

				}
			}
		}
		return $resultSet;
	}
	public function executeCode($sql){
		$servername = "bef6b79c70211c3276b05deefcaf1947b0df66f5.rackspaceclouddb.com";
		$username = "gcsuser";
		$password = "ITlt$6$4GDS";
		$database = "gcs";
		/*$servername = "172.16.4.27";
		$username = "gcsuser";
		$password = "g4sTeam";
		$database = "gcs_hyla";*/
		$con = \mysqli_connect($servername, $username, $password, $database);
		if (\mysqli_connect_errno()){
			return array();
		}
		$resultSet = \mysqli_query($con, $sql); //print_r($resultSet); exit;
		\mysqli_close($con);
		return $resultSet;
	}
	
	public function baseGetCustomerList(){
		$cn = $this->_em->getConnection();
		$session = $this->_request->getSession();
		$login = $session->get('login');
		$customerid = $login['customerid'];
		
		$criteria = '';
		if(!empty($customerid)){
			$criteria .= " and c.id_sync = ".$customerid;
		}
		$sql = "
			SELECT c.id_sync customer_id, c.customer_name
			FROM gcs_customer c
			WHERE c.isdelete = 0  $criteria
			ORDER BY c.customer_name
		";
		return $cn->fetchAll($sql);;
	}
	public function baseGetLocationList(){
		$cn = $this->_em->getConnection();
		$session = $this->_request->getSession();
		$login = $session->get('login');
		$location = $login['location'];
		$criteria = '';
		if($location != ''){
			$criteria .= " and l.id_sync IN ($location)";
		}
		$sql = "
			SELECT l.`id_sync` location_id, l.location_name, 
				(SELECT c.customer_name FROM gcs_customer c WHERE c.id_sync = l.customerid ) customer_name, l.customerid customer_id
			FROM gcs_location l
			WHERE delif = 0 $criteria
			ORDER BY customer_name, location_name
		";
		return $cn->fetchAll($sql);
	}
	public function baseGetStationList(){
		$session = $this->_request->getSession();
		$login = $session->get('login');
		$location = $login['location'];
		$criteria = '';
		if($location != ''){
			$criteria = " and s.location_id IN ($location)";
		}
		
		$sql = "
			SELECT s.id, s.station_serial, so.user_name customer_id, s.location_id
			FROM gcs_station s INNER JOIN gcs_station_owner so
			ON s.id = so.stationid 
			WHERE so.owner_status = 1 AND s.del_if = 1 AND s.location_id is not null AND s.station_type <> 18 $criteria;
		";
		
		return $this->executeCode($sql);
	}
	public function getFunctionTest($customerid){
		if($customerid != 2719 && $customerid != 2736){
			$diagnosticName = array(
				//'device_fmip' => 'FMI',
				'function_lcdcracked' => 'Glass Cracked',
				'function_checkengraving' => 'Check Engraving',
				'function_checkcarrieractive' => 'Active Unit',
				'function_lcdtest' => 'LCD',
				'function_digitizertest' => 'Digitizer',
				
				
				'function_audiotest' => 'Audio Loop Back', 
				'function_bluetooth' => 'Bluetooth', 
				'function_batterytest' => 'Battery', 
				'function_powerbuttonstatus' => 'Power Button', 
				'function_homebuttonstatus' => 'Home Button', 
				'function_upbuttonstatus' => 'Up Button', 
				'function_downbuttonstatus' => 'Down Button', 
				'function_mutebuttonstatus' => 'Mute Button', 
				'function_keypad' => 'Keypad', 
				'function_callsignal' => 'Call Signal Test', 
				'function_camera' => 'Camera', 
				'function_camerafront' => 'Camera Front', 
				'function_camerarear' => 'Camera Rear', 
				'function_charge' => 'Charge', 
				'function_compass' => 'Compass', 
				'function_cosmetic' => 'Cosmetic', 
				'function_cosmeticfront' => 'Cosmetic Front', 
				'function_cosmeticrear' => 'Cosmetic Rear', 
				'function_cosmeticup' => 'Cosmetic Up', 
				'function_cosmeticdown' => 'Cosmetic Down', 
				'function_cosmeticleft' => 'Cosmetic Left', 
				'function_cosmeticright' => 'Cosmetic Right', 
				'function_lcdtest' => 'LCD', 
				'function_digitizertest' => 'Digitizer', 
				'function_dimming' => 'Dimming', 
				//'' => 'Jailbreak', 
				'function_flash' => 'Flash', 
				'function_gps' => 'GPS', 
				'function_headphone' => 'Headphone', 
				'function_internalspeaker' => 'Internal Speaker', 
				'function_motionsensor' => 'Motion Sensor', 
				'function_proximitysensor' => 'Proximity Sensor', 
				'function_touchscreen' => 'Touch Screen', 
				'function_video' => 'Video Play Back', 
				'function_video_front' => 'Video Play Front',
				'function_vibration' => 'Vibration', 
				'function_wifi' => 'Wifi', 
				'function_NFC' => 'NFC', 
				'function_checkimei' => 'Check IMEI', 
				'function_lightsensor' => 'Light sensor', 
				'function_powerdown' => 'Power down', 
				'function_subkeys' => 'Sub keys', 
				'function_subkeybacklight' => 'Backlights',
				
				'function_calltest' => 'Call Test',
				'function_hovering' => 'Hovering',
				'function_pen' => 'Pen',
				'function_sdcarddetection' => 'SD Card',
				'function_cosface' => 'Cosmetic Surface',
				
				'function_litmuspaper' => 'Litmus Paper',
				'function_power_freeze' => 'Freeze',
				'function_power_intermitter' => 'Power Intermitter',
				'function_power_phone_overheating' => 'Power phone overheating',
				
				'function_jaibreak' => 'Jailbreak',
				'function_cosmeticgrade' => 'Cosmetic Grade',
				'function_sdcardtray' => 'SDCard Tray',
				'function_simcardtray' => 'SimCard Tray',
				'function_sdcarddetection' => 'SDCard Detection',
				'function_simcarddetection' => 'SimCard Detection',
				'gradecolor' => 'Grade Color',
				'function_device_dataport' => 'Charge Port',
				'functiontesttype' => 'Function Test Profile',
				'function_result' => 'Grade'
			);
			asort($diagnosticName);
		}
		else{
			$diagnosticName = array(
				'back' => array(
					'name' => 'Cosmetic - Back',
					'1' => 'Like new',
					'2' => 'Some minor scratch',
					'3' => 'Numerous minor scratch or deep scratch',
					'4' => 'Numerous deep scratch',
					'5' => 'Dented'
				),
				'front' => array(
					'name' => 'Cosmetic - Front',
					'6' => 'Like new',
					'7' => 'Minor scratch',
					'8' => 'Light scratch',
					'9' => 'Heavy scratch',
					'10' => 'Cracked'
				),
				'function_test' => array(
					'name' => 'Functional test',
					'digitizer' => 'Touch screen',
					'microphone' => 'Microphone',
					'speaker' => 'Speaker',
					'headphone' => 'Head phone', //Head phone jack, head phone mic
					'camera' => 'Camera',
					'gps' => 'GPS',
					'wifi' => 'Wifi',
					'call' => 'Baseband',
					'button' => 'Button',// (volume up, volume down, power, home, mute switch)
					'checkfordeadpixel' => 'LCD',
					'compass' => 'Compass',
					'touchid' => 'Touch ID'
				),
				'software_checking' => array(
					'name' => 'Software checking',
					'FMI' => 'FMI',
					'nopower' => 'No power'
				),
				'cosmetic' => array(
					'name' => 'Cosmetic',
					'pinkscreen' => 'Pink screen',
					'openphone' => 'Open phone'
				)
				/*,
				'grade_result' => 'Grade'*/
			);
		}
		return $diagnosticName;
	}
	public function getLocationForLocalTime(){
		$cn = $this->_em->getConnection();
		$session = $this->_request->getSession();
		$login = $session->get('login');
		$location = $login['location'];
		$customerid = $login['customerid'];
		$criteria = '';
		if($location != ''){
			$criteria = " and l.id_sync IN ($location)";
		}
		
		$sql = "
			SELECT 	l.id_sync id, l.location_name, l.plus, l.time_zone,
				(CONCAT((IF(l.plus = 0, '-', '+')), l.time_zone)) time_zone2
			FROM gcs_location l
			WHERE l.delif = 0
		";
		return $cn->fetchAll($sql);;
		return $this->executeCode($sql);
	}
	public function baseGetVendorList(){
		$cn = $this->_em->getConnection();
		$sql = "
			SELECT * FROM gcs_vendor ORDER BY name;
		";
		return $cn->fetchAll($sql);;
		return $this->executeCode($sql);
	}
	public function baseGetTimeZone(){
		$zones_array = array();
		$timestamp = time();
		foreach(timezone_identifiers_list() as $key => $zone) {
			date_default_timezone_set($zone);
			if(!isset($zones_array[date('P', $timestamp)])){
				$zones_array[date('P', $timestamp)] = 'UTC '.date('P', $timestamp);
			}
		}
		return $zones_array;
	}
	public function baseBasicSearch($search, $config){
		$dataSet = new \stdClass();
		$dataSet->datas = array();
		$dataSet->total = 0;
		$table = isset($config->table) ?$config->table  : '';
		$colDel = isset($config->delIf) ?$config->delIf  : '';
		if(empty($table)){
			return $dataSet;
		}
		$colAppend = isset($config->colAppend) ?$config->colAppend  : '';
		$tableJoin = isset($config->tableJoin) ?$config->tableJoin  : '';
		$ordering = isset($config->ordering) ?$config->ordering  : '';
		$page = isset($config->page) ?$config->page  : 1;
		$rowPerPage = isset($config->rowPerPage) ? $config->rowPerPage : 10;
		$start = ($page - 1) * $rowPerPage;
		
		$criteriaPattern = array(
			'customer' => 'in',
			'customerid' => 'in',
			'name' => 'like',
			'site_name' => 'like',
			'time_change' => 'lte_datetime'
		);
		$criteria = '';
		$limit = " LIMIT $start, $rowPerPage";
		if(is_object($search)){
			if(count($search) > 0){
				foreach($search as $k=>$v){
					if(!empty($v)){
						if(isset($criteriaPattern[$k])){
							if($criteriaPattern[$k] == 'in'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."o1.$k IN ($v) ";
							} elseif($criteriaPattern[$k] == 'like'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."o1.$k LIKE '%".$v."%'";
							} elseif($criteriaPattern[$k] == 'gte_datetime'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."o1.$k >= '".(date('Y-m-d H:i:s', strtotime($v)))."'";
							}  elseif($criteriaPattern[$k] == 'lte_datetime'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."o1.$k <= '".(date('Y-m-d H:i:s', strtotime($v)))."'";
							} 
						} else {
							if(!is_numeric($v)){
								$v = "'".$v."'";
							}
							$criteria .= (empty($criteria) ? "" : " AND ") ."o1.$k = $v";
						}
					}
				}
			}
		}
		if($rowPerPage == 0){
			$limit = '';
		}
		if(empty($criteria)){
			if(!empty($colDel)){
				$criteria = "$colDel = 0";
			} else {
				$criteria = 1;
			}
		} elseif(!empty($colDel)) {
			$criteria .= " AND $colDel = 0";
		}
		if(!empty($colAppend)){
			$colAppend = ','.$colAppend;
		}
		$sql = "
			SELECT o1.* $colAppend
			FROM $table o1 $tableJoin
			WHERE $criteria
			$ordering
			$limit;
		";
		$cn = $this->_em->getConnection();
		$datas = $cn->fetchAll($sql);
		$sql = "
			SELECT COUNT(*) total
			FROM $table o1
			WHERE $criteria;
		";
		if($rowPerPage == 0){
			$total = 0;
		} else {
			$total = $cn->fetchAll($sql);
			if(isset($total[0]['total'])){
				$total = $total[0]['total'];
			} else {
				$total = 0;
			}
		}
		$dataSet->datas = $datas;
		$dataSet->total = $total;
		return $dataSet;
	}
	public function baseBasicDelete($search, $config){
		$table = isset($config->table) ?$config->table  : '';
		$colDel = isset($config->delIf) ?$config->delIf  : '';
		if(empty($table) || empty($colDel)){
			return 'Save failed!';
		}
		$criteriaPattern = array(
			'customer' => 'in',
			'name' => 'like',
			'time_change' => 'lte_datetime',
			'id' => 'in'
		);
		$criteria = '';
		if(is_object($search)){
			if(count($search) > 0){
				foreach($search as $k=>$v){
					if(!empty($v)){
						if(isset($criteriaPattern[$k])){
							if($criteriaPattern[$k] == 'in'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."$k IN ($v) ";
							} elseif($criteriaPattern[$k] == 'like'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."$k LIKE '%".$v."%'";
							} elseif($criteriaPattern[$k] == 'gte_datetime'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."$k >= '".(date('Y-m-d H:i:s', strtotime($v)))."'";
							}  elseif($criteriaPattern[$k] == 'lte_datetime'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."$k <= '".(date('Y-m-d H:i:s', strtotime($v)))."'";
							} 
						} else {
							if(!is_numeric($v)){
								$v = "'".$v."'";
							}
							$criteria .= (empty($criteria) ? "" : " AND ") ."$k = $v";
						}
					}
				}
			}
		}
		if(empty($criteria)){
			return -1;
		}
		$sql = "
			UPDATE $table
			SET $colDel = 1
			WHERE $criteria;
		";
		$cn = $this->_em->getConnection();
		$result = $cn->executeQuery($sql);
		if($result){
			return 'Save success!';
		}
		return 'Save failed!';
	}
	public function baseBasicInsert($search, $config){
		$fields = '';
		$values = '';
		$table = isset($config->table) ?$config->table  : '';
		$colDel = isset($config->delIf) ?$config->delIf  : '';
		$colCheckExist = isset($config->checkExist) ?$config->checkExist  : array();
		if(empty($table)){
			return -1;
		}
		$criteria = '';
		$updateTmp = '';
		foreach($search as $field=>$value){
			if(isset($colCheckExist[$field])){
				$criteria .= (empty($criteria) ? "" : " AND ") ."$field = '".$value."'";
			}
			$fields .= ($fields == '' ? "`" : ", `").$field."`";
			if(!is_numeric($value)){
				$values .= ($values == '' ? "'" : ", '") .$value."'";
				$updateTmp .= ($updateTmp == '' ? " SET $field = " : ", $field = '") .$value."'";
			} else{
				$values .= ($values == '' ? "" : ", ").$value;
				$updateTmp .= ($updateTmp == '' ? " SET $field = " : ", $field = ") .$value;
			}
		}
		$cn = $this->_em->getConnection();
		if(!empty($criteria)){
			$sql = "
				SELECT * FROM $table WHERE $criteria;
			";
			$checker = $cn->fetchAll($sql);
			if(count($checker) > 0){
				if(isset($checker[0][$colDel])){
					if($checker[0][$colDel] == 1){
						$sql = "
							UPDATE $table 
								$updateTmp, $colDel = 0
							WHERE $criteria;
						";
						$result = $cn->executeQuery($sql);
						if($result){
							return 'Save success!';
						}
						return 'Save failed!';
					} else {
						return 'The item exist!';
					}
				} else {
					return 'Save failed!';
				}
			}
		}
		$sql = "
			INSERT INTO $table ($fields)
			VALUES ($values);
		";
		$result = $cn->executeQuery($sql);
		if($result){
			return 'Save success!';
		}
		return 'Save failed!';
	}
	public function baseBasicUpdate($search, $config){
		$fields = '';
		$values = '';
		$table = isset($config->table) ?$config->table  : '';
		$colDel = isset($config->delIf) ?$config->delIf  : '';
		$idUpdate = isset($config->idEdit) ?$config->idEdit  : '';
		$colCheckExist = isset($config->checkExist) ?$config->checkExist  : array();
		if(empty($table) || empty($idUpdate)){
			return -1;
		}
		$criteria = '';
		$updateTmp = '';
		foreach($search as $field=>$value){
			if(isset($colCheckExist[$field])){
				$criteria .= (empty($criteria) ? "" : " AND ") ."$field = '".$value."'";
			}
			$fields .= ($fields == '' ? "`" : ", `").$field."`";
			if(!is_numeric($value)){
				$updateTmp .= ($updateTmp == '' ? " SET $field = '" : ", $field = '") .$value."'";
			} else{
				$updateTmp .= ($updateTmp == '' ? " SET $field = " : ", $field = ") .$value;
			}
		}
		$cn = $this->_em->getConnection();
		if(!empty($criteria)){
			$sql = "
				SELECT * FROM $table WHERE $criteria AND id <> $idUpdate;
			";
			$checker = $cn->fetchAll($sql);
			if(count($checker) > 0){
				if(isset($checker[0][$colDel])){
					if($checker[0][$colDel] == 1){
						$sql = "
							UPDATE $table 
								$updateTmp, $colDel = 0
							WHERE id = $idUpdate;
						";
						$result = $cn->executeQuery($sql);
						if($result){
							return 'Save success!';
						}
						return 'Save failed!';
					} else {
						return 'The item exist!';
					}
				} else {
					return 'Save failed!';
				}
			} else {
				$sql = "
					UPDATE $table 
						$updateTmp, $colDel = 0
					WHERE id = $idUpdate;
				";
				$result = $cn->executeQuery($sql);
				if($result){
					return 'Save success!';
				}
			}
		}
		return 'Save failed!';
	}
	public function baseCurl($key = '', $url = '', $string = '', $debug = false){
		#region encode
		if(!empty($key)){
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			$encode = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $string, MCRYPT_MODE_ECB, $iv));
		} else {
			$encode = $string;
		}
		#end region encode
		
		#region post data
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
			echo 'Response1: <br />';
			print_r($result); echo '<hr />';
		}
		#end region post data
		
		#region decode result
		if(!empty($key)){
			$decrypt = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($result), MCRYPT_MODE_ECB);
			$json = trim($decrypt);
			$resultSet = json_decode($json, true);
		} else {
			$resultSet = json_decode($result, true);
		}
		
		if($debug){
			echo 'Response2: <br />';
			print_r($resultSet); exit;
		}
		
		return $resultSet;
		#end region decode result
	}
	
}