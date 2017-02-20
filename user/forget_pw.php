<?PHP
	require_once('../config_db/Config_db.php');
	require_once('../session/create_session.php');
	require_once('../session/checking.php');
	require_once('../session/input_replace.php');
	require_once('../session/redirect_page.php');
	require_once('../user/verify.php');
	require_once('../includes/get_today.php');
	require_once('../includes/gen_id.php');
	require_once('../user/gen_token.php');
	require_once('../user/mail.php');
	require_once('salt.php');
	
	/* inital functions */
	start_session(10);
	
	if(check_login()){
		response_message2rediect("You are already login", "../home.php");
	}
	
	$db = db_connect('root', '');
	
	/* main */
	if($_SERVER["REQUEST_METHOD"] == "POST" && check_post_from($_SERVER['HTTP_REFERER'], 'http://localhost/EIE3117_trading_web/user/forget_pw_page.php')) {
		
		/* checking */
		if (check_variable($_POST['Username']) && check_variable($_POST['Email_address'])){
			$username = $_POST['Username'];
			$email_address = $_POST['Email_address'];
		} else {
			response_message2rediect("Please fullin the form!", "./forget_pw_page.php");
		}
		
		if (!(verify_captcha($_POST['g-recaptcha-response']))){
			//echo 'Captcha is no ok';
			response_message2rediect("Please check the captcha form!", "./forget_pw_page.php");
			die();
		}
		
		if (!(valid_email($email_address))){
			response_message2rediect("Email address is not valid!", "./forget_pw_page.php");
			die();
		}	
		
		/* checking end */
		
		$username = input_replace($username);
		$email_address = input_replace($email_address);
		
		try{
			$sql = "Select Tweb_User_ID from Tweb_User where BINARY Tweb_User_Name = :username and Tweb_User_Email = :email_address LIMIT 1;";
			#printf("sql: %s <br/>", $sql);
			$result = $db->prepare($sql);
			$result->bindValue(':username', $username, PDO::PARAM_STR);
			$result->bindValue(':email_address', $email_address, PDO::PARAM_STR);
			$result->execute();
			$rows_user = $result->fetch(PDO::FETCH_NUM);
			
			//print_r($rows_user);
			
			if(!($rows_user)){
				response_message2rediect("Username or Email is wrong", "./login.php");
				$db = null;
				die();
			} else {
				$today = get_today();
				//print('today:'.$today);
				$now_time = time();
				//get token + time
				$token = gen_token($salt ,$username, 'forgot', $today, $now_time);
				
				$token_exptime = $token[1];
				$token = $token[0];
				
				$update_sql = "Update Tweb_User set `Tweb_User_Activation_token` = '".$token."', 
								`Tweb_User_Activation_token_exptime` = '".$token_exptime."'
								where Tweb_User_ID = :user_id and Tweb_User_Email = :email_address";
				$result = $db->prepare($update_sql);
				$result->bindValue(':user_id', $rows_user[0], PDO::PARAM_STR);
				$result->bindValue(':email_address', $email_address, PDO::PARAM_STR);
				$result->execute();
				
				$url = "<a href='http://localhost/EIE3117_trading_web/user/reset_forget_pw.php?email=".$email_address."&verify=".$token."' target=
						'_blank'>http://localhost/EIE3117_trading_web/user/reset_forget_pw.php?email=".$email_address."&verify=".$token."</a><br/>";
				
				send_forget_pw_email($token, $email_address, $url);
				
				response_message2rediect("Email was been sent, please have a check.", "./login.php");
			}

		} catch(PDOException $e){
			$m = $e->getMessage();
			echo $m;
			response_message2rediect("Register fail", "./forget_pw_page.php");
			die();
		}
		
		
	}else{
		response_message2rediect("Bye!", "register.html");
	}	
	
	
?>