<?PHP
	require($_SERVER['DOCUMENT_ROOT'].'/config_db/config_db.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
	require($_SERVER['DOCUMENT_ROOT'].'/user/gen_token.php');
	require($_SERVER['DOCUMENT_ROOT'].'/includes/get_today.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');
	require($_SERVER['DOCUMENT_ROOT'].'/user/salt.php');

	/* inital functions */
	start_session();

	//if(check_login()){
	//	response_message2rediect("You are already login", "../home.php");
	//}

	$db = db_connect('root', '');

	if(check_request_method() == 'POST') {
		if (check_variable($_POST['New_Password']) && check_variable($_POST['Retype_Password'])){
			$new_password = $_POST['New_Password'];
			$retype_password = $_POST['Retype_Password'];
			if($new_password != $retype_password){
				response_message2rediect("Password and retype password are not same! Please click the link again!", "../home.php");
				session_destroy();
				die();
			}
			
			$new_password = hash('sha256', $salt.$new_password);
			try{
				$username = $_SESSION['login_user'];
				$user_id = $_SESSION['login_user_id'];
				$sql = "Select Tweb_User_ID, Tweb_User_Activation_token_exptime from Tweb_User where Tweb_User_ID = :user_id";
				$result = $db->prepare($sql);
				$result->bindValue(':user_id', $user_id);
				$result->execute();
				$rows = $result->fetch(PDO::FETCH_NUM);

				if($rows){
					$user_id = $rows[0];
					$token_exptime = $rows[1];

					if($token_exptime == '0'){
						response_message2rediect("Token was been used!", "../home.php");
						session_destroy();
						$db = null;
						die();
					}
				} else {
					response_message2rediect("Sorry, the code is not vaild", "../home.php");
					session_destroy();
					$db = null;
					die();
				}

				$update_sql = "Update Tweb_User set Tweb_User_Password = '".$new_password."' where BINARY Tweb_User_Name = :username ";
				$result = $db->prepare($update_sql);
				$result->bindValue(':username', $username, PDO::PARAM_STR);
				$result->execute();

				$update_sql = "Update Tweb_User set Tweb_User_Activated = '1', Tweb_User_Activation_token_exptime = '0'
									where Tweb_User_ID = '".$user_id."';";

				$result = $db->prepare($update_sql);
				$result->execute();
				session_destroy();
				response_message2rediect("Reset OK!", "../home.php");
				$db = null;
				die();

			} catch(PDOException $e){
				$m = $e->getMessage();
				//echo $m;
				session_destroy();
				response_message2rediect("Reset fail! Please click the link again!", "../home.php");
				die();
			}

		}
	} else {

		$token = stripslashes(trim($_GET['verify']));
		$email = stripslashes(trim($_GET['email']));
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
						print($_SESSION['login_user']);
						echo "<h4>Token is vaild.Please reset password.<h4><br/>";

		echo '<link rel="stylesheet" text="text/css" href="./form.css" >';
		echo '<script src="https://www.google.com/recaptcha/api.js" async defer></script>';
		echo '<div class="container">';
			echo '<div class="form form-container.form">';
				echo '<h1>Reset Password</h1>';
				echo '<form class="form-horizontal" action="./reset_forget_pw.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">';
					
					echo '<div class="form-group">';
						echo '<input type="password" name="New_Password"  class="form-control" pattern="[0-9a-zA-Z]{5,40}$"
								placeholder="New Password"
								title="New Password should only contain at less 5 alphanumerics."
								autocomplete="off" required />';
					echo '</div>';
					
					echo '<div class="form-group">';
						echo '<input type="password" name="Retype_Password"  class="form-control" pattern="[0-9a-zA-Z]{5,40}$"
								placeholder="Retype-Password"
								title="Password should only contain at less 5 alphanumerics."
								autocomplete="off" required />';
					echo '</div>';
					
					echo '<div class="form-group">';
						echo '<div class="g-recaptcha" data-sitekey="6LePghUUAAAAAFNjJdhM3cpSbcv_EzaODhXZOLtg"></div>';
					//echo '</div>';
					
					echo '<div class="form-group">';
						echo '<input name="login" type="submit" value="Submit">';
					echo '</div>';
					
				echo '</form>';

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
	}
	

?>