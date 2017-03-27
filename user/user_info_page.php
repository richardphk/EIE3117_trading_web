<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/page_gen.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');
	include($_SERVER['DOCUMENT_ROOT'].'/config_db/config_db.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/includes/gen_id.php');
	//external
	include($_SERVER['DOCUMENT_ROOT'].'/tBTC/tBTC_fns.php');

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
			<td>
				<?php
					 if(!empty($rec_credit)){
						echo $rec_credit[0]['Tweb_User_Credit_Cash'];
					 }
				?>
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
				<?php
					if(empty($rec_credit)){
						echo '<button type="submit" name="createWallet" class="btn btn-primary">Create Wallet</button>';
					} else{
						$wallet = init_wallet($uid, $upw, $client);
						$balance = wallet_balance($wallet);
						#print_r($balance);
						printf("%s: %f BTC <br/>", "Comfirmed Balance", $balance[0]);
						printf("%s: %f BTC <br/>", "Uncomfirmed Balance", $balance[1]);
							echo '</td>';
						echo '</tr>';

						echo '<td>Bitcon: New address</td>';
						echo '<td>';
							echo '<button type="submit" name="Get_new_receive_address" class="btn btn-primary">Get New Receive Address</button>';

					}
				?>
			</td>

		</tr>
		<tr>
			<td>Bitcon: Account Transactions History</td>
			<td>
				<?php
					$history = list_tran_history($wallet);
					foreach ($history as $key => $row) {
						if($key == 'data'){
							#print('size of row: '.sizeof($row).'<br/>');
							echo "<ul>";
							foreach ($row as $row_name => $col) {
									#print('size of value: '.sizeof($col).'<br/>');
									echo "<li>";
									foreach ($col as $col_name => $element) {
										if(in_array($col_name, ['hash','time', 'address', 'fee', 'wallet_value_change'])){
											print $col_name.':';
											print_r($element);
											print '<br/>';
										}
									}
									echo "</li>";
							}
							echo "</ul>";
						}
					}
				?>
			</td>
		</tr>
	</table>
</form>

<?PHP

	function create_wallet_account($user_id, $user_pw, $client){
		$db_conn = db_connect('root','root');
		$result_credit = $db_conn->prepare('SELECT Tweb_User_Credit_ID, Tweb_User_Credit_Cash FROM Tweb_User_Credit WHERE Tweb_User_ID = :id;');

		$result_credit->bindValue(':id', $user_id);
		$result_credit->execute();
		$rec_credit =  $result_credit->fetchAll(PDO::FETCH_ASSOC);

		#print_r($rec_credit);

		if(empty($rec_credit)){
			$wallet = create_wallet($user_id, $user_pw, $client);
			$new_credit_id = gen_id("Tweb_User_Credit");
			#echo $new_credit_id;
			$sql = "INSERT INTO `trading_web`.`Tweb_User_Credit`(`Tweb_User_Credit_id`, `Tweb_User_ID`, `Tweb_User_Credit_Cash`, `Tweb_User_Credi_Bitcon_Pin`) VALUES (:new_credit_id, :user_id, 10000000000, :user_pw);";

			$result_new_credit_ac = $db_conn->prepare($sql);
			$result_new_credit_ac->bindValue(':new_credit_id', $new_credit_id);
			$result_new_credit_ac->bindValue(':user_id', $user_id);
			$result_new_credit_ac->bindParam(':user_pw', $user_pw);
			$result_new_credit_ac->execute();
			response_message2rediect('Thank you for using bitcoin!', './user/user_info_page.php');
		} else {
			response_message2rediect('You already have a account!', '../home.php');
		}
	}

	function get_new_receive_address($wallet){
		#$wallet = create_wallet($user_id, $user_pw, $client);
		$receive_address = receive_tran($wallet);
		response_message2rediect('Receive address: '.$receive_address, './user/user_info_page.php');
	}

	function addValue($value,$uid){

			$db_conn = db_connect('root','root');
			$result = $db_conn->prepare("UPDATE tweb_user_credit
										SET Tweb_User_Credit_Cash = :val
										WHERE Tweb_User_ID = :uid ;");
			/*
			$result = $db_conn->prepare("INSERT INTO `Trading_Web`.`Tweb_User_Credit`(`Tweb_User_Credit_id`, `Tweb_User_ID`, `Tweb_User_Credit_Cash`) VALUES ('C00004', 'U00004', 10000000000);");
			*/
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
		#addValue($_POST['Credit'],$uid);
		#create_wallet_account($uid, $upw, $client);
		get_new_receive_address($wallet);
	}

	page_footer();
?>