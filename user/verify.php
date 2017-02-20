<?PHP
	function verify_captcha($recaptcha){
		require_once('..\includes\src\autoload.php');
	
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$secret_key = '6LePghUUAAAAAN0DDxNLJYZea85nFJ8Gg26WwpXh';
			
		if (check_variable($recaptcha)){
			$recaptcha = new \ReCaptcha\ReCaptcha($secret_key);
			$rp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
			$var = var_export($_POST, true);
			
			if ($rp->isSuccess()){
				return true;
			} else {
				return false;
			}
		}
		
	}
	
	
	function valid_email($address) {
		// check an email address is possibly valid
		if (!(filter_var($address, FILTER_VALIDATE_EMAIL))) {
			return false;
		}
		
		return true;
	}

?>