<?PHP
	require_once('../config_db/Config_db.php');
	require_once('../session/create_session.php');
	require_once('../session/checking.php');
	require_once('../user/gen_token.php');
	require_once('../includes/get_today.php');
	require_once('../session/redirect_page.php');
	
	$token = stripslashes(trim($_GET['verify']));
	$email = stripslashes(trim($_GET['email']));
	
	/* inital functions */
	start_session(10);
	
	if(check_login()){
		response_message2rediect("You are already login", "../home.php");
	}
	
	$db = db_connect('root', '');
	
	try{
		$sql = "select * from Tweb_User where Tweb_User_Email=:email LIMIT 1;";
		$result = $db->prepare($sql);
		$result->bindValue(':email', $email, PDO::PARAM_STR);
		$result->execute();
		$rows = $result->fetch(PDO::FETCH_NUM);
		
		if($rows){
			$ds = $rows[9];
			$exptime = $rows[10];
			if($ds == $token){
				if(time() - $exptime > 2*60*60){
					response_message2rediect("Token is not vaild.Try Agnin.", "./login.php");
					$db = null;
					die();
				} else {
					$_SESSION['login_user'] = $rows[1];
					$_SESSION['login_user_id'] = $rows[0];
					$_SESSION['login_user_privilege'] = $rows[6];
					response_message2rediect("Token is vaild.Please reset password.", "./reset_pw_page.php");
					$db = null;
					die();
				}
			
			} else {
				response_message2rediect("Token error.", "./login.php");
				$db = null;
				die();
			
			}
		} else{
			response_message2rediect("It is not exist.", "./login.php");
			$db = null;
			die();
		}
		
	
	} catch (PDOException $e){
		response_message2rediect("It is not exist2.", "./login.php");
		$db = null;
		die();
	
	}
	
	
	

?>