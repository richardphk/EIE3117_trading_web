<?PHP
	function send_active_mail($username, $token, $email_address){
		$headers = "From: support@e-trading-web.com \r\n";
		$headers .= "Reply-To: ". $email_address . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$message = '<html><body>';
		$message .= '<div> Dear '.$username.', </div> <br/>';
		$message .= '<h4>Thank you for your registration!</h4><br/>';
		$message .= '<h6>Please click the below link to active the account.</h6><br/>';
		$message .= '<h7>The code will not be vaild after 2 hours.</h7><br/>';
		$message .= "<a href='http://158.132.145.246/EIE3117_trading_web/user/active.php?verify=".$token."' target=
						'_blank'>http://158.132.145.246/EIE3117_trading_web/user/active.php?verify=".$token."</a><br/>";
		
		
		$message_footer = '<br/> <div> Best wish, </div> <br/> <div> e-trading-web support </div> <br/>';
		$message_footer .= '</body></html>';
		
		if (mail($email_address, 'E-trading-web.com Account Activation', $message.$message_footer , $headers)) {
			return true;
		} else {
			throw new Exception($check); 
		}
	}
	
	function send_forget_pw_email($token, $email_address, $url){
		$headers = "From: support@e-trading-web.com \r\n";
		$headers .= "Reply-To: ". $email_address . "\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

		$message = '<html><body>';
		$message .= '<div> Dear User, </div> <br/>';
		$message .= '<h4>Here is the reset password link!</h4><br/>';
		$message .= '<h6>The code will not be vaild after 2 hours.</h6><br/>';
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