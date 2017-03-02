<?PHP
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/config_db/config_db.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/create_session.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/checking.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/input_replace.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/redirect_page.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/user/verify.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/includes/get_today.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/includes/gen_id.php');
	include('salt.php');
	
	/* inital functions */
	start_session(10000);
	
	if(!(check_login())){
		response_message2rediect("You have not login", "./login.php");
	}
	
	$db = db_connect('root', '');
	
	/* main */
	if($_SERVER["REQUEST_METHOD"] == "POST" && check_post_from($_SERVER['HTTP_REFERER'], 'user/reset_pw_page.php')) {
		
		/* checking */
		if (check_variable($_POST['Old_Password']) && check_variable($_POST['New_Password'])
				&& check_variable($_POST['Retype_Password'])){
			
			$old_password = $_POST['Old_Password'];
			$new_password = $_POST['New_Password'];
			$retype_password = $_POST['Retype_Password'];
			
		} else {
			response_message2rediect("Please fullin the form!", "./reset_pw_page.php");
		}
		
		if (!(verify_captcha($_POST['g-recaptcha-response']))){
			//echo 'Captcha is no ok';
			response_message2rediect("Please check the captcha form!", "./reset_pw_page.php");
			die();
		}
		
		if($new_password != $retype_password){
			response_message2rediect("New password and retype password are not same!", "./reset_pw_page.php");
			die();
		}
		
		/* checking end */
		
		$old_password = input_replace($old_password);
		$new_password = input_replace($new_password);
		$retype_password = input_replace($retype_password);
		$old_password = hash('sha256', $salt.$old_password);
		$new_password = hash('sha256', $salt.$new_password);
		//$retype_password = hash('sha256', $salt.$retype_password);

		
		try{
			$username = $_SESSION['login_user'];
			
			$sql = "Select Tweb_User_ID, Tweb_User_Name, Tweb_User_Privilege from Tweb_User where BINARY Tweb_User_Name = :username and Tweb_User_Password = '".$old_password."' LIMIT 1;";
			#printf("sql: %s <br/>", $sql);
			$result = $db->prepare($sql);
			$result->bindValue(':username', $username, PDO::PARAM_STR);
			$result->execute();
			$rows_user = $result->fetch(PDO::FETCH_NUM);
			
			if($rows_user){
				$update_sql = "Update Tweb_User set Tweb_User_Password = '".$new_password."' where BINARY Tweb_User_Name = :username ";
				$result = $db->prepare($update_sql);
				$result->bindValue(':username', $username, PDO::PARAM_STR);
				$result->execute();
				response_message2rediect("Reset OK!", "../home.php");
				$db = null;
				die();
			} else {
				response_message2rediect("Wrong Password!", "./reset_pw_page.php");
				$db = null;
				die();
			}

		} catch(PDOException $e){
			$m = $e->getMessage();
			//echo $m;
			response_message2rediect("Reset fail!", "./reset_pw_page.php");
			die();
		}
		
		
	}else{
		response_message2rediect("Bye!", "./reset_pw_page.php");
	}	
	
	
?>