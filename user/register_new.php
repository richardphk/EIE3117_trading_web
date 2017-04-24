<?PHP
	/**
	 * this is the php function to register in database
	 */
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
	require($_SERVER['DOCUMENT_ROOT'].'/user/salt.php');

	/* inital functions */
	start_session();
	
	if(check_login()){
		response_message2rediect("You are already login", "../home.php");
	}
	
	$db = db_connect('root','root');
	
	/* main */
	
	//remark bugs: check_post_from($_SERVER['HTTP_REFERER'], 'user/register.php')

	if(!($_SERVER["REQUEST_METHOD"] == "POST")) {
		response_message2rediect("Bye!", "register.php");
	}
	
		/* checking */
		if (check_variable($_POST['Username']) && check_variable($_POST['Password']) && check_variable($_POST['Nickname'])
			&& check_variable($_POST['Retype_Password']) && check_variable($_POST['Email_address']) 
			&& check_variable($_POST['dob']) && check_variable($_POST['Phone'])){
			$username = $_POST['Username'];
			$nickname = $_POST['Nickname'];
			$password = $_POST['Password'];
			$retype_password = $_POST['Retype_Password'];
			$email_address = $_POST['Email_address'];
			$dob = $_POST['dob'];
			$phone = $_POST['Phone'];
		} else {
			response_message2rediect("Please fullin the form!", "./register.php");
		}


		if (!(verify_captcha($_POST['g-recaptcha-response']))){
			//check captcha
			//echo 'Captcha is no ok';
			response_message2rediect("Please check the captcha form!", "./register.php");
			die();
		}

		if(!isset($_SESSION['flag']) || $_SESSION['flag'] !== $_POST['token']){
			response_message2rediect("Please check the captcha form, too!", "./login.php");
			die();
		}

		
		if($password != $retype_password){
			response_message2rediect("Password and retype password are not same!", "./register.php");
			die();
		}
		
		if($username == $password){
			response_message2rediect("Password and username should not be same!", "./register.php");
			die();
		}
		
		if (!(valid_email($email_address))){
			response_message2rediect("Email address is not valid!", "./register.php");
			die();
		}
		
		/* checking end */
		
		$username = input_replace($username);
		$nickname = input_replace($nickname);
		$password = input_replace($password);
		$retype_password = input_replace($retype_password);
		$email_address = input_replace($email_address);
		$dob = input_replace($dob);
		$phone = input_replace($phone);
		$password = hash('sha256', $salt.$password);
		
		/*
		printf("username:%s <br/>", $username);
		printf("nickname:%s <br/>", $nickname);
		printf("password:%s <br/>", $password);
		printf("retype_password:%s <br/>", $retype_password);
		printf("email_address:%s <br/>", $email_address);
		printf("dob:%s <br/>", $dob);
		printf("phone:%s <br/>", $phone);
		*/
		
		try{
			$sql = "Select Tweb_User_ID from Tweb_User where BINARY Tweb_User_Name = :username LIMIT 1;";
			#printf("sql: %s <br/>", $sql);
			$result = $db->prepare($sql);
			$result->bindValue(':username', $username, PDO::PARAM_STR);
			$result->execute();
			$rows_username = $result->fetch(PDO::FETCH_NUM);
			
			$sql = "Select Tweb_User_ID from Tweb_User where Tweb_User_Email = :email_address LIMIT 1;";
			#printf("sql: %s <br/>", $sql);
			$result = $db->prepare($sql);
			$result->bindValue(':email_address', $email_address, PDO::PARAM_STR);
			$result->execute();
			$rows_email = $result->fetch(PDO::FETCH_NUM);
			
			if($rows_username){
				response_message2rediect("Sorry, username have been used", "register.php");
				die();
			}else if($rows_email){
				response_message2rediect("Sorry, the email address have been used", "register.php");
				die();
			}else{
				$new_user_id = gen_id('Tweb_User');
				//print('new id:'.$new_user_id);
				$today = get_today();
				//print('today:'.$today);
				$now_time = time();
				//get token + time
				$token = gen_token($salt ,$username, $password, $today, $now_time);
				
				$token_exptime = $token[1];
				$token = $token[0];

				$sql = "INSERT INTO `trading_web`.`Tweb_User` 
						(`Tweb_User_ID`, `Tweb_User_Name`, `Tweb_User_Password`, `Tweb_User_Birthday`, `Tweb_User_Email`, `Tweb_User_Phone`, `Tweb_User_Privilege`, `Tweb_User_Nickname`, `Tweb_User_Activated`, `Tweb_User_Activation_token`, `Tweb_User_Activation_token_exptime`) 
						VALUES 
						(:id, :username, '".$password."', :dob, :email, :phone, 'User', :nickname, '0', '".$token."', ".$token_exptime.");";
				
				//printf("sql:%s <br/>", $sql);
				$result = $db->prepare($sql);
				$result->bindValue(':id', $new_user_id, PDO::PARAM_STR);
				$result->bindValue(':username', $username, PDO::PARAM_STR);
				$password = hash('sha256', $salt.$password);
				//echo '<br/>'.'ok here';
				$result->bindValue(':dob', $dob, PDO::PARAM_STR);
				$result->bindValue(':email', $email_address, PDO::PARAM_STR);
				$result->bindValue(':phone', $phone, PDO::PARAM_STR);
				
				$result->bindValue(':nickname', $nickname, PDO::PARAM_STR);
				$result->execute();
				//printf("<br/> sql:%s <br/>", $sql);
				
				send_active_mail($username, $token, $email_address);
				
				//printf("The activation email has been sent...<br/>  rediect to %s after 3 seconds <br/>", 'Home Page');
				response_message2rediect("Register OK! The activation email has been sent & rediect to home page ", "../home.php");
				//header("Location:home.php");//
			}

		} catch(PDOException $e){
			$m = $e->getMessage();
			//echo $m;
			response_message2rediect("Register fail", "./register.php");
			die();
		}
	
	
?>