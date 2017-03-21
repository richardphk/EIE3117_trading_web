<?PHP
	require($_SERVER['DOCUMENT_ROOT'].'/config_db/config_db.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/input_replace.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');
	require($_SERVER['DOCUMENT_ROOT'].'/user/verify.php');
	require($_SERVER['DOCUMENT_ROOT'].'/includes/get_today.php');
	require($_SERVER['DOCUMENT_ROOT'].'/includes/gen_id.php');
	require($_SERVER['DOCUMENT_ROOT'].'/user/gen_token.php');
	require($_SERVER['DOCUMENT_ROOT'].'/user/mail.php');
	require_once('salt.php');
	
	/* inital functions */
	start_session();
	
	if(check_login()){
		response_message2rediect("You are already login", "../home.php");
	}
	
	$db = db_connect('root', '');
	
	/* main */
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		
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

				send_forget_pw_email($token, $email_address);
				
				response_message2rediect("Email was been sent, please have a check.", "./login.php");
			}

		} catch(PDOException $e){
			$m = $e->getMessage();
			echo $m;
			response_message2rediect("Register fail", "./forget_pw_page.php");
			die();
		}
		
	}else{
		response_message2rediect("Bye!", "./register.php");
	}
?>