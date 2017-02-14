<?php
	function check_login(){
		if(isset($_SESSION['login_user'])){
			return true;
		}else{
			return false;
		}
	}

	function check_request_method(){
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			return 'POST';
		} else if ($_SERVER["REQUEST_METHOD"] == "GET"){
			return 'GET';
		}
		return false;
	}
	
	function check_array($array){
		if(!(empty($array)) && isset($array) && $array != ''){
			return true;
		}
		return false;
	}
	
?>