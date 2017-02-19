<?PHP
	/* inital functions */
	require_once('../session/create_session.php');
	require_once('../session/checking.php');
	require_once('../session/redirect_page.php');
	start_session(10);
	
	if(check_login()){
		response_message2rediect("You are already login", "../home.php");
	}

	require_once('../page_gen.php');
	page_header('Login Page');
?>
	<link rel="stylesheet" text="text/css" href="http://localhost/EIE3117_trading_web/user/form.css" >
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<div class="container">
		<div class="form form-container.form">
			<h1>Log in</h1>
			<form class="form-horizontal" action="./user/user_login.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
				
				<div class="form-group">
					<input type="text" name="Username" class="form-control" pattern="[0-9a-zA-Z]{5,40}$" 
							placeholder="Username"
							title="Username should only contain at less 5 alphanumerics." autocomplete="off" required />
				</div>
				
				<div class="form-group">
					<input type="password" name="Password"  class="form-control" pattern="[0-9a-zA-Z]{5,40}$" 
							placeholder="Password"
							title="Password should only contain at less 5 alphanumerics." autocomplete="off" required />
				</div>
				
				<div class="form-group">
					<div class="g-recaptcha" data-sitekey="6LePghUUAAAAAFNjJdhM3cpSbcv_EzaODhXZOLtg"></div>
				</div>
				
				<div class="form-group">
					<label><input name="Remember" type="checkbox"> Remember me</label>
				</div>
				
				<div class="form-group">
					<input name="login" type="submit" value="Sign in">
				</div>
				
				<div class="form-group">
					<label>Not registered? </label>	
					<input type="button" class="btn btn-default" onclick="javascript:location.href='./user/register.php'" value="Create an account">
				</div>
				
			</form>
		</div> <!-- form -->
	</div> <!-- container -->
<?PHP
	page_footer();
?>