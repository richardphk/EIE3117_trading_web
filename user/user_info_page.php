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
	$result = $db_conn->prepare('SELECT c.Tweb_User_ID, u.Tweb_User_Name, u.Tweb_User_Email, c.Tweb_User_Credit_Cash FROM tweb_user as u, tweb_user_credit as c 
									WHERE u.Tweb_User_ID = c.Tweb_User_ID and u.Tweb_User_ID = :id;');
	$result->bindValue(':id', $id);
	$result->execute();
	$rec = $result->fetchAll(PDO::FETCH_ASSOC);
	$uid = $rec[0]['Tweb_User_ID'];
	#print($uid);
	#print_r($rec);
	

	

?>


<h2>User Information</h2>
<form method="POST" style="margin-bottom: 0px;" action="">
	<input type="hidden" name= 'uid' value='<?php echo $uid;?>' ></input>

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
				  <input type="text" name="Credit" class="form-control" placeholder="value" pattern="\d*" />
					<span class="input-group-btn">
						<button  class="btn btn-default" type="submit" >Enter</button>
					</span>
					
				</div>
			</td>
		</tr>	
		<tr>
			<td>Bitcon: Account</td>
			<td>
				<div class="input-group">
				  <input type="text" name="Baddress" class="form-control" placeholder="Address">
					<span class="input-group-btn">
						<button name="Baddress" class="btn btn-default" type="submit">Enter</button>
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

	function addValue($value,$uid){

		
			$db_conn = db_connect('root','root');
			$result = $db_conn->prepare("UPDATE tweb_user_credit
										SET Tweb_User_Credit_Cash = :val
										WHERE Tweb_User_ID = :uid ;");
			$result->bindValue(':val', $value);
			$result->bindValue(':uid', $uid);
			$result->execute();
			
		
			 echo '<meta http-equiv="refresh" content="1"/>';
			
		
			#header("Refresh:0; url=page2.php");
			echo '<div class="alert alert-success alert-dismissable">
			  <a href="#" class="close" data-dismiss="alert" aria-label="close" >&times;</a>
			  <strong>Success!</strong> credit changed.
				</div>';
			
	}
	
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			addValue($_POST['Credit'],$uid);
			
		}
	
	page_footer();
?>