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
			'customer_name' => 'like',
			'address' => 'like',
			'phone' => 'like'
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
		$cn = $this->objConnection->getConnection();
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
		$cn = $this->objConnection->getConnection();
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
		$cn = $this->objConnection->getConnection();
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
		$cn = $this->objConnection->getConnection();
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