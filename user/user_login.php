<?PHP
	require_once('../config_db/Config_db.php');
	require_once('../session/create_session.php');
	require_once('../session/checking.php');
	require_once('../session/input_replace.php');
	require_once('../session/redirect_page.php');
	include('salt.php');
	
	/* inital functions */
	start_session(10);
	
	if(check_login()){
		response_message2rediect("You are already login", "login.php");
	}
	
	$db = db_connect('root', '');
	
	/* main */
	if(check_request_method() == 'POST') {
		$username = $_POST['Username'];
		$password = $_POST['Password'];
		
		$username = input_replace($username);
		$password = input_replace($password);
		$password = hash('sha256', $salt.$password);
		
		#printf("username:%s <br/>", $username);
		#printf("password:%s <br/>", $password);
		
		try{
			$sql = "Select Tweb_User_ID, Tweb_User_Name, Tweb_User_Privilege from Tweb_User where BINARY Tweb_User_Name = :username and Tweb_User_Password = '".$password."' LIMIT 1;";
			#printf("sql: %s <br/>", $sql);
			$result = $db->prepare($sql);
			$result->bindValue(':username', $username, PDO::PARAM_STR);
			$result->execute();
			$rows = $result->fetch(PDO::FETCH_NUM);
			
			#print_r($rows);
			
			if($rows){
				$_SESSION['login_user'] = $username;
				$_SESSION['login_user_id'] = $rows[0];
				$_SESSION['login_user_privilege'] = $rows[1];
				
				response_message2rediect("Welcome back!", "../home.php");
				$db = null;
				include('update_behavior.php');
				die();
			}else{
				response_message2rediect("Wrong User name or password!", "login.php");
				$db = null;
				die();
			}
		}catch(PDOException $e){
			$m = $e->getMessage();
			echo $m;
			response_message2rediect("Log-in fail!", "login.php");
			$db = null;
			die();
		}
	}else{
		response_message2rediect("Bye!", "login.php");
		$db = null;
	}
	
?>