<?PHP
	require_once($_SERVER['DOCUMENT_ROOT'].'/config_db/config_db.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/input_replace.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/user/verify.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/includes/get_today.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/user/salt.php');
	
	/* inital functions */
	start_session();
	
	if(check_login()){
		response_message2rediect("You are already login", "../home.php");
	}
	
	$db = db_connect('root', '');
	//print($_SERVER['HTTP_REFERER']);
	
	/* main */
	if(check_request_method() == 'POST') {
		if (check_variable($_POST['Username']) && check_variable($_POST['Password'])){
			$username = $_POST['Username'];
			$password = $_POST['Password'];
			if (isset($_POST['Remember'])){
				$remember_me = $_POST['Remember'];
			} else {
				$remember_me = false;
			}
		} else {
			response_message2rediect("Please fullin the form!", "./login.php");
		}
		
		if (!(verify_captcha($_POST['g-recaptcha-response']))){
			//echo 'Captcha is no ok';
			response_message2rediect("Please check the captcha form!", "./login.php");
			die();
		}
		
		$username = input_replace($username);
		$password = input_replace($password);
		$password = hash('sha256', $salt.$password);

		//printf("username:%s <br/>", $username);
		//printf("password:%s <br/>", $password);
		
		try{
			$sql = "Select Tweb_User_ID, Tweb_User_Name, Tweb_User_Privilege, Tweb_User_Activated from Tweb_User where BINARY Tweb_User_Name = :username and Tweb_User_Password = '".$password."' LIMIT 1;";
			#printf("sql: %s <br/>", $sql);
			$result = $db->prepare($sql);
			$result->bindValue(':username', $username, PDO::PARAM_STR);
			$result->execute();
			$rows = $result->fetch(PDO::FETCH_NUM);
			//echo '<br/>hi sql';
			//print_r($rows);
			
			if($rows){
				$login_user_id = $rows[0];
				$login_user = $rows[1];
				$login_user_privilege = $rows[2];
				$login_active = $rows[3];
			
				if($login_active == 0){
					$db = null;
					response_message2rediect("Sorry, you have not activated!", "../home.php");
					die();
				} else {
					$_SESSION['login_user'] = $login_user;
					$_SESSION['login_user_id'] = $login_user_id;
					$_SESSION['login_user_privilege'] = $login_user_privilege;
					
					//print($remember_me);
					
					if($remember_me == 'on'){
						//print($remember_me.'<br/>');
						$cookie_name = 'user';
						$today = get_today();
						$now_time = time();
						$secret = hash('sha256', $salt.$login_user.$login_user_id.$salt.$login_user_id.$salt);
						$cookie_name = 'login_cookie';
						$expiry = time() + (86400 * 30);
						
						if(!(isset($_COOKIE['user']))){
							setcookie('user', $secret.'-'.$login_user.'-'.$login_user_id , $expiry);
						}
						
						//print_r($_COOKIE['user']);
					}
					
					$db = null;
					response_message2rediect("Welcome back!", "../home.php");
					die();
				}
			}else{
				response_message2rediect("Wrong User name or password!", "./login.php");
				$db = null;
				die();
			}
			
		}catch(PDOException $e){
			$m = $e->getMessage();
			//echo $m;
			response_message2rediect("Log-in fail!", "./login.php");
			$db = null;
			die();
		}
		
	}else{
		echo 'Error';
		response_message2rediect("Bye!", "./login.php");
		$db = null;
		die();
	}
	
?>
