<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/page_gen.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/config_db/config_db.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/tBTC/tBTC_fns.php');

	#print $wallet;
	page_header('User Info');

	if(!(check_login())){
		response_message2rediect("Please login first!", "../home.php");
		exit();
	}

	$id = $_SESSION['login_user_id'];
	//echo $id;
	$db_conn = db_connect('root','root');
	$result = $db_conn->prepare('SELECT Tweb_User_ID, Tweb_User_Name, Tweb_User_Email, Tweb_User_Password FROM Tweb_User where Tweb_User_ID = :id;');
	$result->bindValue(':id', $id);
	$result->execute();

	$rec = $result->fetchAll(PDO::FETCH_ASSOC);
	$uid = $rec[0]['Tweb_User_ID'];
	$upw = $rec[0]['Tweb_User_Password'];

	#print($uid);
	#print($upw);
	#print_r($rec);

	$result_credit = $db_conn->prepare('SELECT * FROM Tweb_User_Credit WHERE Tweb_User_ID = :id;');

	$result_credit->bindValue(':id', $id);
	$result_credit->execute();
	$rec_credit =  $result_credit->fetchAll(PDO::FETCH_ASSOC);

	#print($uid);
	#print_r($rec_credit);
	#print('hi'.check_variable($rec_credit).'hi');

?>


<h2>User Information</h2>
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
			<td>
				<?php
					 if(!empty($rec_credit)){
						echo $rec_credit[0]['Tweb_User_Credit_Cash'];
						echo '<form method="POST" name="addcredit" style="margin-bottom: 0px;" action="">';
						echo '<div class="input-group" style="width:180px;">
					  	<input type="text" name="Credit" class="form-control" placeholder="value" pattern="\d*" />';
						echo '<span class="input-group-btn">
							<button  class="btn btn-default" type="submit" >Enter</button>';
						echo '</span>';
						echo '</form>';
					 }
				?>

				</div>
			</td>
		</tr>

		<tr>
			<td>Bitcon: Account</td>
			<td>
				<?php
						if(empty($rec_credit)){
							echo '<form name="BITCON_create" method="POST" action="/user/user_info_page_fns.php">';
							echo '<button type="submit" name="createWallet" class="btn btn-primary">Create Wallet</button></form>';
						} else{
							$wallet = init_wallet($uid, $upw, $client);
							$balance = wallet_balance($wallet);
							#print_r($balance);
							printf("%s: %f BTC <br/>", "Comfirmed Balance", $balance[0]);
							printf("%s: %f BTC <br/>", "Uncomfirmed Balance", $balance[1]);
								echo '</td>';
							echo '</tr>';
							echo '<form name="BITCON_get_new_address" method="POST" action="">';
							echo '<td>Bitcon: New address</td>';
							echo '<td>';
								echo '<button type="submit" name="Get_new_receive_address" class="btn btn-primary">Get New Receive Address</button>';
							echo "</form>";

						}
				?>
			</td>

		</tr>
		<tr>
			<td>Bitcon: Account Transactions History</td>
			<td>
			<?php
				if(isset($wallet)){
					$history = list_tran_history($wallet);
					if(!(empty($history))){
						foreach ($history as $key => $row) {
							if($key == 'data'){
								#print('size of row: '.sizeof($row).'<br/>');
								echo "<ul>";
								foreach ($row as $row_name => $col) {
										#print('size of value: '.sizeof($col).'<br/>');
										echo "<li>";
										foreach ($col as $col_name => $element) {
											if(in_array($col_name, ['hash', 'time', 'address', 'fee', 'wallet_value_change'])){

												if($col_name == 'hash'){
													print 'Transactions HASH : '.$element.'<br/>';
												} else if($col_name == 'time'){
													print 'Time : '.$element.'<br/>';
												} else if($col_name == 'wallet_value_change'){
													$balance_record = sprintf("%2.2f",($element/100000000));
													print 'Wallet Value Change : '.$balance_record.' BTC <br/>';
												}
											}
										}
										echo "</li>";
								}
								echo "</ul>";
							}
						}
					} else {
						print "No Record.<br/>";
					}
				}
			?>
			</td>
		</tr>
	</table>


<?PHP

	function addValue($value,$uid){

			$db_conn = db_connect('root','root');
			$result = $db_conn->prepare("UPDATE Tweb_User_Credit
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

	function get_new_receive_address($wallet){
		$receive_address = receive_tran($wallet);
		response_message2rediect('Receive address: '.$receive_address, './user/user_info_page.php');
	}

	#print($wallet);

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['Credit']) && !empty($_POST['Credit'])){
			addValue($_POST['Credit'],$uid);
			unset($_POST['Credit']);

		}
		elseif(isset($wallet)){
			get_new_receive_address($wallet);
		}
	}

	page_footer();
?>