<?php
	function start_session($expire = 10){
		if ($expire == 0){
			$expire = ini_get('session.gc_maxlifetime');
		}else{
			ini_set('session.gc_maxlifetime', $expire);
		}
		if(empty($_COOKIE['PHPSESSID'])){
			session_set_cookie_params($expire);
			session_start();
		}else{
			session_start();
			setcookie('PHPSESSID', session_id(), time() + $expire);
		}
	}
?>