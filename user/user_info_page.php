<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/page_gen.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/config_db/config_db.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/user/user_info_page_fns.php');

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
	if(!empty($rec_credit)){
		$rec_ac_cash = $rec_credit[0]['Tweb_User_Credit_Cash'];
		$receive_address = $rec_credit[0]['Tweb_User_Bitcon_RevAddress'];
	}
	#print($uid);
	#print_r($rec_credit);
	#print('hi'.check_variable($rec_credit).'hi');

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['Credit']) && !empty($_POST['Credit'])){
			addValue($_POST['Credit'],$uid);
			unset($_POST['Credit']);
		}else if(isset($_POST['createWallet'])){
			create_wallet_account($uid, $upw, $client);

		}else if(isset($_POST['Get_new_receive_address'])){
			$receive_address = get_new_receive_address($uid, $upw, $client);

		}else if(isset($_POST['bit2cash_bitcoin'])){
			client_bitcoin_to_cash($uid, $upw, $client, $_POST['bit2cash_bitcoin'], $rec_ac_cash);

		}else if(isset($_POST['cash2bit_cash'])){
			client_cash_to_bitcoin($uid, $receive_address, $client, $_POST['cash2bit_cash'], $rec_ac_cash);
		}else if (isset($_POST['C2C_bitcoin'])){
			C2C_bitcoin_transfer($uid, $upw, $client, $_POST['C2C_bitcoin'], $_POST['revaddr']);
			
		}
	}

?>


<h2>User Information</h2>
	<input type="hidden" name= 'uid' value='<?php echo $uid;?>' ></input>

	<table class="table table-hover" style="width:70%;">
		<tr>
			<td>User Name:</td>
			<td><?php echo $rec[0]['Tweb_User_Name']?></td>
		</tr>
		<tr>
			<td>Email:</td>
			<td><?php echo $rec[0]['Tweb_User_Email']?></td>
		</tr>
		<tr>
			<?php
				if(!empty($rec_credit)){
					echo "<td>Credit Account:</td>";
					echo "<td>";
						echo '$'.$rec_ac_cash;
						echo '<form method="POST" name="addcredit" style="margin-bottom: 0px;" action="">';
						echo '<div class="input-group" style="width:180px;">
					  	<input type="text" name="Credit" class="form-control" placeholder="Add Cash" pattern="\d*" />';
						echo '<span class="input-group-btn">
							<button  class="btn btn-default" type="submit" >Enter</button>';
						echo '</span>';
						echo "</div>";
						echo '</form>';
					echo "</td>";
				}
			?>
		</tr>

		<tr>
			<td>Bitcon Account: </td>
			<td>
				<?php
					if(empty($rec_credit)){
						echo '<form name="BITCON_create" method="POST" action="">';
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
						echo '<td>Bitcon Address: </td>';
						echo '<td>';
						echo 'Now receive address: '.$receive_address.'<br/>';
						echo '<button type="submit" name="Get_new_receive_address" class="btn btn-primary">Get New Receive Address</button>';
						echo "</form>";

					}
				?>
			</td>
		</tr>
		<?php
			if(!empty($rec_credit)){
				echo "<tr>";
					echo "<td>Bitcoin -> Cash</td>";
					echo "<td>";
						echo '<form method="POST" name="bit2cash" style="margin-bottom: 0px;" action="">';
							echo '<div class="input-group" style="width:180px;">';
						  	echo '<input type="number" name="bit2cash_bitcoin" class="form-control" placeholder="Bitcoin (BTC)" step="any" />';
							echo '<span class="input-group-btn">';
							echo '<button  class="btn btn-default" type="submit" >Enter</button>';
							echo '</span>';
							echo "</div>";
						echo "</form>";
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>Cash -> Bitcoin</td>";
					echo "<td>";
						echo '<form method="POST" name="cash2bit" style="margin-bottom: 0px;" action="">';
							echo '<div class="input-group" style="width:200px;">';
						  	echo '<input type="text" name="cash2bit_cash" class="form-control" placeholder="Cash ($)" pattern="\d*" />';
							echo '<span class="input-group-btn">';
							echo '<button  class="btn btn-default" type="submit" >Enter</button>';
							echo '</span>';
							echo "</div>";
						echo "</form>";
					echo "</td>";
				echo "</tr>";

				echo "<tr>";
					echo "<td>C2C Bitcon</td>";
					echo "<td>";
						echo '<form method="POST" name="C2C" style="margin-bottom: 0px;" action="">';
							echo '<div class="input-group" style="width:300px; display:grid;">';
						  	echo 'Value: <input type="number" name="C2C_bitcoin" class="form-control" placeholder="Bitcoin (BTC)" step="any" /><br>';
							echo ' Receiver Address: <input type="text" name="revaddr" class="form-control" placeholder="address" />';
							echo '<p align="right"><span align="right" class="input-group-btn">';
							echo '<button  class="btn btn-default" type="submit"  >Enter</button>';
							echo '</span></p>';
							echo "</div>";
						echo "</form>";
					echo "</td>";
				echo "</tr>";
			}
		?>
		<tr>
			<?php
				if(isset($wallet)){
					echo "<td>Bitcon: Account Transactions History</td>";
					echo "<td>";
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
												if($col_name == 'time'){
													$date = date("Y/m/d", strtotime($element));
													$time = date("H:i:s a", strtotime($element));
													print 'Date : '.$date.'<br/>';
													print 'Time : '.$time.'<br/>';
												} else  if($col_name == 'hash'){
													$hash = $element;
													print 'Transactions HASH : '.$hash.'<br/>';
												} else if($col_name == 'wallet_value_change'){
													$balance_record = sprintf("%2.8f",($element/100000000));
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
				echo "</td>";
			?>

		</tr>
	</table>


<?PHP
	page_footer();
?>