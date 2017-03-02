<?PHP
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/config_db/config_db.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/checking.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/redirect_page.php');
	
	$now_time = time();
	$db = db_connect('root', '');
	
	if(check_request_method() == 'GET' && check_variable($_GET['verify'])){
		$verify = stripslashes(trim($_GET['verify']));
		$sql = "Select Tweb_User_ID, Tweb_User_Activation_token_exptime from Tweb_User where Tweb_User_Activation_token = :verify and Tweb_User_Activated = 0  LIMIT 1;";
		$result = $db->prepare($sql);
		$result->bindValue(':verify', $verify);
		$result->execute();
		$rows = $result->fetch(PDO::FETCH_NUM);
		
		//print($verify.'<br/>');
		//print_r($rows);
		//print('<br/>'.$now_time.'<br/>');
		
		if($rows){
			$user_id = $rows[0];
			$token_exptime = $rows[1];
			
			if($token_exptime == '0'){
				response_message2rediect("Token was been used!", "../home.php");
				$db = null;
				die();
			}
			
			if($now_time > $token_exptime){
				response_message2rediect("Sorry, the code is not vaild, please try to login & resend the code again", "../home.php");
				$db = null;
				die();
			} else {
				try{
					$update_sql = "Update Tweb_User set Tweb_User_Activated = '1', Tweb_User_Activation_token_exptime = 'NULL'
									where Tweb_User_ID = '".$user_id."';";
					//print($update_sql);
					$result = $db->prepare($update_sql);
					$result->execute();
					
					response_message2rediect("Activation sccuessful!", "../home.php");
					$db = null;
					die();
				} catch(PDOException $e){
					$m = $e->getMessage();
					echo $m;
					response_message2rediect("Active fail", "../home.php");
					die();
				}
			}
			
		} else {
			response_message2rediect("Sorry, the code is not vaild", "../home.php");
			$db = null;
			die();
		}
		
	}else{
		response_message2rediect("Bye!", "../home.php");
		$db = null;
		die();
	}	

?>