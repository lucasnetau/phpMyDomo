<?php
class PMD_Page extends PMD_Root_Page{

	//----------------------------------------------------------------------------------
	function Run(){
		isset($_GET['mode'])	and $mode	=$_GET['mode'];
		
		if($mode=='set'){
			$this->_modeSet();
		}
		elseif($mode=='list_devices'){
			$this->_modeListDevices();			
		}
		
		$this->_jsonError();
	}

	//----------------------------------------------------------------------------------
	private function _modeSet(){
		if($this->conf['app']['demo_api_limit']){
			$this->_jsonError();			
		}
		isset($_GET['a'])	and $address	=$_GET['a'];
		isset($_GET['v'])	and $val		=$_GET['v'];
		isset($_GET['t'])	and $type		=$_GET['t'];
		isset($_GET['i'])	and $invert		=$_GET['i'];
		if($address and strlen($val)){
			$type or $type='switch'; // or scene
			if($this->o_api->ApiFetch('set', $type, $address , $val,$invert)){
				$this->_jsonOk();
				exit;
			}
			$this->_jsonError();
		}
	}

	//----------------------------------------------------------------------------------
	private function _modeListDevices(){
		if($this->conf['app']['demo_api_limit']){
			$this->_jsonError();			
		}
		isset($_GET['c'])	and $class	=$_GET['c'];
		isset($_GET['t'])	and $type	=$_GET['t'];
		if($arr['data']=$this->o_api->GetDevices($class,$type)){
			$this->_jsonOk($arr);
			exit;
		}
		$this->_jsonError();
	}

	//----------------------------------------------------------------------------------
	private function _jsonOk($arr=array()){
		$arr['status'] or $arr['status']='ok';
		echo json_encode($arr);
	}

	//----------------------------------------------------------------------------------
	private function _jsonError($exit=1){
		$arr=array();
		$arr['status']		='err';
		$arr['api_url']		=$this->o_api->api_url;
		$arr['api_response']	=$this->o_api->api_response;
		echo json_encode($arr);
		if($exit){
			exit;
		}
	}	
} 
?>