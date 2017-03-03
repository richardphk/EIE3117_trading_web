<?php
	function check_login(){
		//$_SESSION['login_user'] -> username
		//$_SESSION['login_user_id'] -> user_id
		//$_SESSION['login_user_privilege'] -> no for now
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
	
	function check_variable($array){
		if(!(empty($array)) && isset($array) && $array != ''){
			return true;
		}
		return false;
	}
	
	function check_post_from($url, $real_url){
		if ($url == $real_url){
			return true;
		}
		return false;
	}
	
?>