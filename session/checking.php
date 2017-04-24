<?php
	/**
 	* check login in session
 	* @return [boolean] true or false
 	*/
	function check_login(){
	/** check user session login or not **/
		//$_SESSION['login_user'] -> username
		//$_SESSION['login_user_id'] -> user_id
		//$_SESSION['login_user_privilege'] -> no for now
		if(isset($_SESSION['login_user'])){
			return true;
		}else{
			return false;
		}
	}
	/**
	 * check request method
	 * @return [type] POST,GET or false -> error
	 */
	function check_request_method(){
	/** check POST or GET **/
		if ($_SERVER["REQUEST_METHOD"] == "POST"){
			return 'POST';
		} else if ($_SERVER["REQUEST_METHOD"] == "GET"){
			return 'GET';
		}
		return false;
	}

	/**
	 * check variable exist, empty
	 * @param  [Unknown] input
	 * @return [boolean] variable is seted and not empty -> true else false
	 */
	function check_variable($array){
	/** check variable exist and not empty **/
		if(!(empty($array)) && isset($array) && $array != ''){
			return true;
		}
		return false;
	}

	/**
	 * check refer header
	 * @param  [String] $url      [http refer in php]
	 * @param  [String] $real_url [Real refer]
	 * @return [boolean] true or not
	 */
	function check_post_from($url, $real_url){
	/** check refer url **/
		if ($url == $real_url){
			return true;
		}
		return false;
	}

?>