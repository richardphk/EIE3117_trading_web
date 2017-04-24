<?PHP
	/**
	 * the page of reset password
	 */
	/* inital functions */
	require($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
	require($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');

	if(check_login()){
		response_message2rediect("You have not login", "./login.php");
	}

	require($_SERVER['DOCUMENT_ROOT'].'/page_gen.php');
	page_header('Reset Password Page');

?>
	<link rel="stylesheet" text="text/css" href="user/form.css" >
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<div class="container">
		<div class="form form-container.form">
			<h1>Reset Password</h1>
			<form class="form-horizontal" action="./user/reset_pw.php" method="post" accept-charset="utf-8" enctype="multipart/form-data">
				
				<div class="form-group">
					<input type="password" name="Old_Password"  class="form-control" pattern="[0-9a-zA-Z]{5,40}$"
							placeholder="Old_Password"
							title="Old Password should only contain at less 5 alphanumerics."
							autocomplete="off" required />
				</div>
				
				<div class="form-group">
					<input type="password" name="New_Password"  class="form-control" pattern="[0-9a-zA-Z]{5,40}$"
							placeholder="New Password"
							title="New Password should only contain at less 5 alphanumerics."
							autocomplete="off" required />
				</div>
				
				<div class="form-group">
					<input type="password" name="Retype_Password"  class="form-control" pattern="[0-9a-zA-Z]{5,40}$"
							placeholder="Retype-Password"
							title="Password should only contain at less 5 alphanumerics."
							autocomplete="off" required />
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