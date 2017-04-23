<?PHP
	/* inital functions */
	require($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');

	if(check_login()){
		response_message2rediect("You are already login", "../home.php");
	}

	require($_SERVER['DOCUMENT_ROOT'].'/page_gen.php');
	page_header('Register Page');

	$key = hash('sha256', microtime());
	$_SESSION['flag'] = $key;

?>
	<link rel="stylesheet" text="text/css" href="user/form.css" >
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<div class="container">
		<div class="form form-container.form">
			<h1>Register</h1>
			<form class="form-horizontal" action="./user/register_new.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">

				<div class="form-group">
					<input type="text" name='Username' placeholder="Username"
							 pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{5,40}"
							 autocomplete="off"
							 title="Username should only contain at least 5(MAX:40) characters that are of at least one number, one uppercase and lowercase letter."
							 required />
				</div>

				<div class="form-group">
					<input type="text" name='Nickname' placeholder="Nickname"
							title="Nickname"
							autocomplete="off"
							required />
				</div>

				<div class="form-group">
					<label>Date of birth:</label>
					<input type="date" name="dob" autocomplete="off" required />
				</div>

				<div class="form-group">
					<input type="email" name='Email_address'
							placeholder="Email address" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"
							title="Email format xxx@xxx.xxx(all lowercase)"
							autocomplete="off"
							required />
				</div>

				<div class="form-group">
					<input type="text" name='Phone'  placeholder="Phone Number" pattern="[0-9]{8,15}$"
							pattern="[0-9]{8,14}$"
							title="Phone Number should only contain at least 8 numbers"
							autocomplete="off"
							required />
				</div>

				<div class="form-group">
					<input type="password" name="Password"  class="form-control" pattern="[0-9a-zA-Z]{5,40}$"
							placeholder="Password"
							title="Password should only contain at less 5 alphanumerics."
							autocomplete="off" required />
				</div>

				<div class="form-group">
					<input type="password" name="Retype_Password"  class="form-control" pattern="[0-9a-zA-Z]{5,40}$"
							placeholder="Retype-Password"
							title="Password should only contain at less 5 alphanumerics."
							autocomplete="off" required />
				</div>

				<input type="hidden" name="token" value="<?php echo $key; ?>" />
				
				<div class="form-group">
					<div class="g-recaptcha" data-sitekey="6LePghUUAAAAAFNjJdhM3cpSbcv_EzaODhXZOLtg"></div>
				</div>

				<div class="form-group">
					<input name="login" type="submit" value="Submit">
				</div>

				<div class="form-group">
					<label>Already registered? </label>
					<input type="button" class="btn btn-default" onclick="javascript:location.href='./user/login.php'" value="Sign In">
				</div>

			</form>
		</div> <!-- form -->
	</div> <!-- container -->

<?PHP
	page_footer();
?>