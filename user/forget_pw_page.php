<?PHP
	/* inital functions */
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/create_session.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/checking.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/redirect_page.php');
	
	if(check_login()){
		response_message2rediect("You are already login", "../home.php");
	}

	require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/page_gen.php');
	page_header('Forget Password Page');

?>
	<link rel="stylesheet" text="text/css" href="user/form.css" >
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<div class="container">
		<div class="form form-container.form">
			<h1>Forget Password</h1>
			<form class="form-horizontal" action="./user/forget_pw.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
				
				<div class="form-group">
					<input type="text" name='Username' placeholder="Username" 
							pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,40}"
							autocomplete="off" 
							title="Username should only contain at least 5(MAX:40) characters that are of at least one number, one uppercase and lowercase letter." 
							required />
				</div>
				
				<div class="form-group">
					<input type="email" name='Email_address'  
							placeholder="Email address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"
							title="Email format xxx@xxx.xxx(all lowercase)"
							autocomplete="off" 
							required />
				</div>
				
				<div class="form-group">
					<div class="g-recaptcha" data-sitekey="6LePghUUAAAAAFNjJdhM3cpSbcv_EzaODhXZOLtg"></div>
				</div>
				
				<div class="form-group">
					<input name="login" type="submit" value="Submit">
				</div>
				
			</form>
		</div> <!-- form -->
	</div> <!-- container -->

<?PHP
	page_footer();
?>