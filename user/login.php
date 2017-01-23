<?PHP
	include('../Config_db/Config_db_normal_user.php');
	include('salt.php');
	
	start_session(10);
	
	/* functions */
	function start_session($expire){
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
	
	function input_replace($str){
		# whatever small special code replacing
		$str = str_replace('--', '', $str);
		$str = str_replace(' ', '', $str);
		$str = str_replace('&nbsp;', '', $str);
		return $str;
	}
	
	function response_message2rediect($msg, $page){
		echo '<script type="text/javascript">';
		echo 'alert("'.$msg.'");';
		echo 'window.location.href = "'.$page.'"';
		echo '</script>';
	}
	
	if(isset($_SESSION['login_user'])){
		response_message2rediect("already login", "../home.php");
	}
	
	/* main */
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = $_POST['Username'];
		$password = $_POST['Password'];
		
		$username = input_replace($username);
		$password = input_replace($password);
		$password = hash('sha256', $salt.$password);
		
		#printf("username:%s <br/>", $username);
		#printf("password:%s <br/>", $password);
		
		try{
			$sql = "Select User_ID, User_Privilege from e_user where BINARY User_Name = :username and User_Password = '".$password."' LIMIT 1;";
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
				response_message2rediect("Wrong User name or password!", "login.html");
				$db = null;
				die();
			}
		}catch(PDOException $e){
			$m = $e->getMessage();
			#echo $m;
			response_message2rediect("Log-in fail!", "login.html");
			$db = null;
			die();
		}
	}else{
		response_message2rediect("Bye!", "login.html");
		$db = null;
	}
	
?>