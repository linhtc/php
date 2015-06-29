<?php
namespace SmartCafe\ServiceBundle\Model;

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
    	$sessionLogin = $this->objSession->get('profile'); //print_r($sessionLogin); exit;
    	if(is_object($sessionLogin)){
    		$pattern = $class.'___'.$uri;
    		if(!isset($sessionLogin->username)){
    			return $ctrl->redirect($ctrl->generateUrl('sc_login'));
    		} elseif(!isset($sessionLogin->permission->$class->$action) && !$sessionLogin->admin){
    			return $ctrl->redirect($ctrl->generateUrl('sc_permission_denied'));
    		} else {
    			return $sessionLogin;
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
		$table = isset($config->table) ? $config->table  : '';
		$aliasTable = isset($config->aliasTable) ? $config->aliasTable  : 'o1';
		$colDel = isset($config->delIf) ? $config->delIf  : '';
		$colSpecify = isset($config->colSpecify) ? $config->colSpecify  : "$aliasTable.*";
		if(empty($table)){
			return $dataSet;
		}
		$colAppend = isset($config->colAppend) ? $config->colAppend  : '';
		$tableJoin = isset($config->tableJoin) ? $config->tableJoin  : '';
		$ordering = isset($config->ordering) ? $config->ordering  : '';
		$page = isset($config->page) ? $config->page  : 1;
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
								$criteria .= (empty($criteria) ? "" : " AND ") ."$aliasTable.$k IN ($v) ";
							} elseif($criteriaPattern[$k] == 'like'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."$aliasTable.$k LIKE '%".$v."%'";
							} elseif($criteriaPattern[$k] == 'gte_datetime'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."$aliasTable.$k >= '".(date('Y-m-d H:i:s', strtotime($v)))."'";
							}  elseif($criteriaPattern[$k] == 'lte_datetime'){
								$criteria .= (empty($criteria) ? "" : " AND ") ."$aliasTable.$k <= '".(date('Y-m-d H:i:s', strtotime($v)))."'";
							} 
						} else {
							if(!is_numeric($v)){
								$v = "'".$v."'";
							}
							$criteria .= (empty($criteria) ? "" : " AND ") ."$aliasTable.$k = $v";
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
			SELECT $colSpecify $colAppend
			FROM $table $aliasTable $tableJoin
			WHERE $criteria
			$ordering
			$limit;
		"; //echo $sql; exit;
		$cn = $this->objConnection->getConnection();
		$datas = $cn->fetchAll($sql);
		$sql = "
			SELECT COUNT(*) total
			FROM $table $aliasTable
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
				$updateTmp .= ($updateTmp == '' ? " SET $field = '" : ", $field = '") .$value."'";
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
	public function baseGetPageMap($div = '', $page = 1, $totalitems, $limit = 10, $adjacents = 1, $targetpage = "/", $pagestring = "?page="){
		//defaults
		if(!$adjacents) $adjacents = 1;
		if(!$limit) $limit = 15;
		if(!$page) $page = 1;
		if(!$targetpage) $targetpage = "/";
		 
		//other vars
		$prev = $page - 1;									//previous page is page - 1
		$next = $page + 1;									//next page is page + 1
		$lastpage = ceil($totalitems / $limit);				//lastpage is = total items / items per page, rounded up.
		$lpm1 = $lastpage - 1;								//last page minus 1
		 
		/*
		 Now we apply our rules and draw the pagination object.
		 We're actually saving the code to a variable in case we want to draw it more than once.
		 */
		$pagination = "";
		if($lastpage > 1){
	
			$pagination .= "<ul class=\"pagination pagination-sm\">";
				
			//first button
			if ($page > 1)
				$pagination .= 	"<li >
				<a href=\"javascript:baseGetPageMap('$div','1','$targetpage$pagestring".'1'."')\">
										First
									</a>
								</li>";
			else
				$pagination .= 	"<li class='prev disabled'>
				<a href=\"javascript:baseGetPageMap('$div','1','$targetpage$pagestring".'1'."')\">
										First
									</a>
								</li>";
			//previous button
			if ($page > 1)
				$pagination .= "<li >
				<a href=\"javascript:baseGetPageMap('$div','$prev','$targetpage$pagestring$prev')\">
				<i class=\"fa fa-angle-left\"></i>
				</a>
				</li>";
			//$pagination .= "<a href=\"$targetpage$pagestring$prev\">« prev</a>";
			else
				//$pagination .= "<span class=\"disabled\">« prev</span>";
				$pagination .= "<li class='prev disabled'>
									<a href=\"javascript:;\">
										<i class=\"fa fa-angle-left\"></i>
									</a>
								</li>";
	
			//pages
			if ($lastpage < 7 + ($adjacents * 2)){	//not enough pages to bother breaking it up
				for ($counter = 1; $counter <= $lastpage; $counter++){
					if ($counter == $page){
						$pagination .= "<li class='active'><span class=\"current\">$counter</span></li>";
					} else{
						$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','$counter','$targetpage$pagestring$counter')\">$counter</a></li>";
					}
				}
			} elseif($lastpage >= 7 + ($adjacents * 2)){	//enough pages to hide some
					//close to beginning; only hide later pages
						if($page < 1 + ($adjacents * 3)){
							for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++){
								if ($counter == $page){
								$pagination .= "<li class='active'><span class=\"current\">$counter</span></li>";
								} else{
									$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','$counter','$targetpage$pagestring$counter')\">$counter</a></li>";
								}
							}
							$pagination .= "<li><span class=\"elipses\">...</span></li>";
							$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','$lpm1','$targetpage$pagestring$lpm1')\">$lpm1</a></li>";
							$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','$lastpage','$targetpage$pagestring$lastpage')\">$lastpage</a></li>";
						} elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){ //in middle; hide some front and some back
							$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','1','$targetpage$pagestring".'1'."')\">1</a></li>";
							$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','2','$targetpage$pagestring".'2'."')\">2</a></li>";
							$pagination .= "<li><span class=\"elipses\">...</span></li>";
    						for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
		    					if ($counter == $page){
		    						$pagination .= "<li class='active'><span class=\"current\">$counter</span></li>";
		    					} else {
		    						$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','$counter','$targetpage$pagestring$counter')\">$counter</a></li>";
		    					}
	    					}
		    				//$pagination .= "...";
		    				$pagination .= "<li><span class=\"elipses\">...</span></li>";
	    					$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','$lpm1','$targetpage$pagestring$lpm1')\">$lpm1</a></li>";
	    					$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','$lastpage','$targetpage$pagestring$lastpage')\">$lastpage</a></li>";
						} else { //close to end; only hide early pages
							$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','1','$targetpage$pagestring".'1'."')\">1</a></li>";
							$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','2','$targetpage$pagestring".'2'."')\">2</a></li>";
							$pagination .= "<li><span class=\"elipses\">...</span></li>";
							for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
								if ($counter == $page){
									$pagination .= "<li class='active'><span class=\"current\">$counter</span></li>";
								} else {
									$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','$counter','$targetpage$pagestring$counter')\">$counter</a></li>";
								}
							}
						}
				}
	
									//next button
				if ($page < $counter - 1){
					$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','$next','$targetpage$pagestring$next')\"><i class=\"fa fa-angle-right\"></i></a></li>";
				} else{
					$pagination .= "<li class='next disabled'><span class=\"disabled\"><i class=\"fa fa-angle-right\"></i></span></li>";
				}
				if ($page < $counter - 1){
    				$pagination .= "<li><a href=\"javascript:baseGetPageMap('$div','".($lastpage)."','$targetpage$pagestring".($lastpage)."')\">Last</a></li>";
				} else{
	    			$pagination .= "<li class='next disabled'><span class=\"disabled\">Last</span></li>";
				}
    		// Them action ra ngoai de xu ly tiep
            $action = explode('action=',$pagestring);
            if(isset($action[1])){
				$action = explode('&',$action[1]);
				$action = $action[0];
			}else{
				$action = '';
			}
			$pagination .= "<li class='pagination-action' style='display:none'>$action</li></ul>\n";
		}
		 
		return $pagination;
	
	}
	
	public function executeQuery($sql){
		$cn = $this->objConnection->getConnection();
		$list = $cn->fetchAll($sql);
		$cn->close();
		return $list;
	}
	public function executeNonQuery($sql){
		$cn = $this->objConnection->getConnection();
		$result = $cn->executeQuery($sql);
		$cn->close();
		return $result;
	}
}