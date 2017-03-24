<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/page_gen.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');
	include_once($_SERVER['DOCUMENT_ROOT'] .'/config_db/config_db.php');

	
	page_header('user_info');
	
	$id = $_SESSION['login_user_id'];
	//echo $id;
	$db_conn = db_connect('root','root');
	$result = $db_conn->prepare('SELECT u.Tweb_User_Name, u.Tweb_User_Email, c.Tweb_User_Credit_Cash FROM tweb_user as u, tweb_user_credit as c 
									WHERE u.Tweb_User_ID = c.Tweb_User_ID and u.Tweb_User_ID = :id;');
	$result->bindValue(':id', $id);
	$result->execute();
	$rec = $result->fetchAll(PDO::FETCH_ASSOC);
	//print_r($rec);
	

	

?>


<h2>User Information</h2>
<form method="POST" style="margin-bottom: 0px;" action="user/user_info_page_fns.php">
	<table class="table table-hover" style="width:51%;">
		<tr>
			<td>User Name:</td>
			<td><?php echo $rec[0]['Tweb_User_Name']?></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><?php echo $rec[0]['Tweb_User_Email']?></td>
		</tr>	
		<tr>
			<td>Credit Account:</td>
			<td><?php print( $rec[0]['Tweb_User_Credit_Cash'])?>
				<div class="input-group" style="width:180px;">
				  <input type="text" class="form-control" placeholder="value" ">
					<span class="input-group-btn">
						<button name="Credit" class="btn btn-default" type="submit">Enter</button>
					</span>
					</input>
				</div>
			</td>
		</tr>	
		<tr>
			<td>Bitcon: Account</td>
			<td>
				<div class="input-group">
				  <input type="text" class="form-control" placeholder="Address">
					<span class="input-group-btn">
						<button name="address" class="btn btn-default" type="submit">Enter</button>
					</span>
					</input>
				</div>
			
			</td>
		</tr>
		<tr>
		
		</tr>
	</table>
</form>
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
<?PHP
	page_footer();
?>