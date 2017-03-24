<?PHP
	function send_active_mail($username, $token, $email_address){
		$host_uri = $_SERVER['HTTP_HOST'];
		$self = explode('/', dirname($_SERVER['PHP_SELF']));
		$folder_eie = $self[1];

		$headers = "From: support@e-trading-web.com \r\n";
		$headers .= "Reply-To: ". $email_address . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$message = '<html><body>';
		$message .= '<div> Dear '.$username.', </div> <br/>';
		$message .= '<h4>Thank you for your registration!</h4><br/>';
		$message .= '<h6>Please click the below link to active the account.</h6><br/>';
		$message .= '<h7>The code will not be vaild after 2 hours.</h7><br/>';
		$message .= "<a href='http://".$host_uri."/".$folder_eie."/active.php?verify=".$token."' target=
						'_blank'>Click me</a><br/>";

		$message_footer = '<br/> <div> Best wish, </div> <br/> <div> e-trading-web support </div> <br/>';
		$message_footer .= '</body></html>';

		try{
			mail($email_address, 'E-trading-web.com Account Activation', $message.$message_footer , $headers);
		}catch(Exception $e){
			print($e);
		}

	}

	function send_forget_pw_email($token, $email_address){
		$host_uri = $_SERVER['HTTP_HOST'];
		$self = explode('/', dirname($_SERVER['PHP_SELF']));
		$folder_eie = $self[1];

		$headers = "From: support@e-trading-web.com \r\n";
		$headers .= "Reply-To: ". $email_address . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$message = '<html><body>';
		$message .= '<div> Dear User, </div> <br/>';
		$message .= '<h4>Here is the reset password link!</h4><br/>';
		$message .= '<h6>The code will not be vaild after 2 hours.</h6><br/>';

		$url = "<a href='http://".$host_uri."/".$folder_eie."/user/reset_forget_pw.php?email=".$email_address."&verify=".$token."' target='_blank'>Click me</a><br/>";

		$message .= $url;

		$message_footer = '<br/> <div> Best wish, </div> <br/> <div> e-trading-web support </div> <br/>';
		$message_footer .= '</body></html>';

		if (mail($email_address, 'E-trading-web.com Forgot Passwood Informaiton', $message.$message_footer , $headers)) {
			return true;
		} else {
			throw new Exception($check);
		}
	}
	
?>