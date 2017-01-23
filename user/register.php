<?PHP
	include('../Config_db/Config_db_register.php');
	include('salt.php');
	
	/* functions */
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
	
	function get_today(){
		return date("Y-m-d");
	}
	
	/* main */
	if($_SERVER["REQUEST_METHOD"] == "POST") {
		$username = $_POST['Username'];
		$password = $_POST['Password'];
		$retype_password = $_POST['Retype_Password'];
		$email_address = $_POST['Email_address'];
		$gender = $_POST['gender'];
		$dob = $_POST['dob'];
		$full_name = $_POST['Full_name'];
		
		#printf("username:%s <br/>", $username);
		#printf("password:%s <br/>", $password);
		#printf("retype_password:%s <br/>", $retype_password);
		#printf("email_address:%s <br/>", $email_address);
		#printf("gender:%s <br/>", $gender);
		#printf("dob:%s <br/>", $dob);
		
		$username = input_replace($username);
		$password = input_replace($password);
		$retype_password = input_replace($retype_password);
		$email_address = input_replace($email_address);
		$gender = input_replace($gender);
		$dob = input_replace($dob);
		$full_name = input_replace($full_name);
		
		if($password != $retype_password){
			response_message2rediect("Password and retype password are not same!", "register.html");
			die();
		}
		
		if($username == $password){
			response_message2rediect("Password and username should not be same!", "register.html");
			die();
		}
		
		try{
			$sql = "Select User_ID from e_user where BINARY User_Name = :username LIMIT 1;";
			#printf("sql: %s <br/>", $sql);
			$result = $db->prepare($sql);
			$result->bindValue(':username', $username, PDO::PARAM_STR);
			$result->execute();
			$rows_username = $result->fetch(PDO::FETCH_NUM);
			
			$sql = "Select User_ID from e_user where User_Email_Address = :email_address LIMIT 1;";
			#printf("sql: %s <br/>", $sql);
			$result = $db->prepare($sql);
			$result->bindValue(':email_address', $email_address, PDO::PARAM_STR);
			$result->execute();
			$rows_email = $result->fetch(PDO::FETCH_NUM);
			
			#print_r($rows_username);
			#print_r($rows_email);
			
			if($rows_username){
				response_message2rediect("Sorry, username have been used", "register.html");
				die();
			}else if($rows_email){
				response_message2rediect("Sorry, the email address have been used", "register.html");
				die();
			}else{
				$sql = "Select User_ID from e_user order by User_ID DESC LIMIT 1;";
				$result = $db->prepare($sql);
				$result->execute();
				$rows = $result->fetch(PDO::FETCH_NUM);
				$result = $rows[0];
				$result = preg_replace("/[^0-9]/","", $result);
				
				#printf("result:%s <br/>", $result);
				
				$new_id = intval($result) + 1;
				$new_id = sprintf("U%05d", $new_id);
				#printf("new_id:%s <br/>", $new_id);
				
				$today = get_today();
				$sql = "INSERT INTO `E_User` (`User_ID`, `User_Password`, `User_Name`, `User_Gender`, `User_DOB`, `User_Privilege`, `User_Full_name`, `User_enable`, `User_Reg_Date`, `User_Email_Address`)
						VALUES ('".$new_id."', :password, :username, :gender, :dob, 'student', :full_name, True, '".$today."', :email_address);";
				$result = $db->prepare($sql);
				$result->bindValue(':username', $username, PDO::PARAM_STR);
				$password = hash('sha256', $salt.$password);
				$result->bindValue(':password', $password, PDO::PARAM_STR);
				$result->bindValue(':gender', $gender, PDO::PARAM_STR);
				$result->bindValue(':dob', $dob, PDO::PARAM_STR);
				$result->bindValue(':full_name', $full_name, PDO::PARAM_STR);
				$result->bindValue(':email_address', $email_address, PDO::PARAM_STR);
				
				#printf("sql:%s <br/>", $sql);
				$result->execute();
				
				response_message2rediect("Register ok", "login.html");
			}
		}catch(PDOException $e){
			$m = $e->getMessage();
			#echo $m;
			response_message2rediect("Register fail", "register.html");
			die();
		}
	}else{
		response_message2rediect("Bye!", "register.html");
	}
	
?>