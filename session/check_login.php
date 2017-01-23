<?PHP
	function check_login(){
		if(isset($_SESSION['login_user'])){
			return true;
		}else{
			return false;
		}
	}
?>