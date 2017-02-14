<?PHP
	require_once('../page_gen.php');
	page_header();
?>
	<div class="jumbotron">
	<h1>Log in</h1>
	<form class="form-horizontal" action="user_login.php" method="post" accept-charset="utf-8">
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Username :</label>
			<div class="col-sm-3">
				<input type="text" name="Username" class="form-control" pattern="[0-9a-zA-Z]{5,40}$" 
						title="Username should only contain at less 5 alphanumerics." autocomplete="off" required />
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Password :</label>
			<div class="col-sm-3">
				<input type="text" name="Password"  class="form-control" pattern="[0-9a-zA-Z]{5,40}$" 
						title="Password should only contain at less 5 alphanumerics." autocomplete="off" required />
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<div class="checkbox">
					<label><input type="checkbox"> Remember me</label>
				</div>
			</div>
		</div>
		
		
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
				<button type="submit" class="btn btn-default">Sign in</button>
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Not registered? </label>	
			<div class="col-sm-offset-2 col-sm-10">
				<input type="button" class="btn btn-default" onclick="javascript:location.href='./register.html'" value="Create an account">
			</div>
		</div>
		
	</form>
	</div>

<?PHP
	page_footer();
?>