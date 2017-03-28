<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/config_db/config_db.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/tBTC/tBTC_fns.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/includes/gen_id.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');

	function create_wallet_account($user_id, $user_pw, $client){
		$db_conn = db_connect('root','root');
		$result_credit = $db_conn->prepare('SELECT Tweb_User_Credit_ID, Tweb_User_Credit_Cash FROM Tweb_User_Credit WHERE Tweb_User_ID = :id;');

		$result_credit->bindValue(':id', $user_id);
		$result_credit->execute();
		$rec_credit =  $result_credit->fetchAll(PDO::FETCH_ASSOC);

		#print_r($rec_credit);

		if(empty($rec_credit)){
			create_wallet($user_id, $user_pw, $client);
			$wallet = init_wallet($user_id, $user_pw, $client);
			$new_receive_address = receive_tran($wallet);

			$new_credit_id = gen_id("Tweb_User_Credit");
			#echo $new_credit_id;
			$sql = "INSERT INTO `trading_web`.`Tweb_User_Credit`(`Tweb_User_Credit_id`, `Tweb_User_ID`, `Tweb_User_Credit_Cash`, `Tweb_User_Credi_Bitcon_Pin`, `Tweb_User_Bitcon_RevAddress`) VALUES (:new_credit_id, :user_id, 100000, :user_pw, :user_address);";

			$result_new_credit_ac = $db_conn->prepare($sql);
			$result_new_credit_ac->bindValue(':new_credit_id', $new_credit_id);
			$result_new_credit_ac->bindValue(':user_id', $user_id);
			$result_new_credit_ac->bindValue(':user_pw', $user_pw);
			$result_new_credit_ac->bindValue(':user_address', $new_receive_address);
			$result_new_credit_ac->execute();
			response_message2rediect('Thank you for using bitcoin!', '/user/user_info_page.php');
		} else {
			response_message2rediect('You already have a account!', '/home.php');
		}
	}

	function addValue($value,$uid){
			$db_conn = db_connect('root','root');
			$result = $db_conn->prepare("UPDATE Tweb_User_Credit
										SET Tweb_User_Credit_Cash = :val
										WHERE Tweb_User_ID = :uid ;");

			$result->bindValue(':val', $value);
			$result->bindValue(':uid', $uid);
			$result->execute();

			//refresh
			echo '<meta http-equiv="refresh" content="1"/>';

			#header("Refresh:0; url=page2.php");
			echo '<div class="alert alert-success alert-dismissable">
			  		<a href="#" class="close" data-dismiss="alert" aria-label="close" >&times;</a>
			  		<strong>Success!</strong> Credit Changed!
					</div>';
	}

	function get_new_receive_address($uid, $upw, $client){
		$wallet = init_wallet($uid, $upw, $client);
		$receive_address = receive_tran($wallet);

		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare("UPDATE Tweb_User_Credit
										SET Tweb_User_Bitcon_RevAddress = :val
										WHERE Tweb_User_ID = :uid ;");

		$result->bindValue(':val', $receive_address);
		$result->bindValue(':uid', $uid);
		$result->execute();

		//refresh
		echo '<meta http-equiv="refresh" content="1"/>';

		#header("Refresh:0; url=page2.php");
		echo '<div class="alert alert-success alert-dismissable">
			  	<a href="#" class="close" data-dismiss="alert" aria-label="close" >&times;</a>
			  	<strong>Success!</strong> Receive Address Update!
				</div>';
		return $receive_address;
	}

	function client_bitcoin_to_cash($uid, $upw, $client, $value, $rec_ac_cash){
		//client wallet , server rececive address, $value
		$server_wallet = init_wallet('Zonetwo', 'Zonetwo2', $client);
		$receive_address = receive_tran($server_wallet);

		$client_wallet = init_wallet($uid, $upw, $client);
		$client_balance = wallet_balance($client_wallet);
		$client_com_balance = $client_balance[0];
		$client_uncom_balance = $client_balance[1];
		$client_total_balance = $client_com_balance + $client_uncom_balance;

		if($client_total_balance < $value){
			response_message2rediect('Sorry! You dont have enough bitcoin!', '/user/user_info_page.php');
		} else{
			//db
			$db_conn = db_connect('root','root');
			$new_cash = $rec_ac_cash + $value * 100000000;
			$result = $db_conn->prepare("UPDATE Tweb_User_Credit
										SET Tweb_User_Credit_Cash = :val
										WHERE Tweb_User_ID = :uid ;");
			$result->bindValue(':val', $new_cash);
			$result->bindValue(':uid', $uid);
			$result->execute();

			//BlockTrail
			send_tran($client_wallet, $receive_address, $value);
			//refresh
			echo '<meta http-equiv="refresh" content="1"/>';

			#header("Refresh:0; url=page2.php");
			echo '<div class="alert alert-success alert-dismissable">
			  		<a href="#" class="close" data-dismiss="alert" aria-label="close" >&times;</a>
			  		<strong>Success!</strong> Bitcoin to Cash Success!
					</div>';
		}
		#send_tran($tran_wallet, $receive_address, $value);
	}

	function client_cash_to_bitcoin($uid, $receive_address, $client, $value, $rec_ac_cash){
		//server wallet , user rececive address, $value
		$server_wallet = init_wallet('Zonetwo', 'Zonetwo2', $client);
		$server_balance = wallet_balance($server_wallet);
		$server_balance = $server_balance[0];
		$request_bitcoin = $value/100000000;

		if($request_bitcoin > $server_balance[0]){
			response_message2rediect('Sorry! Server dont have enough bitcoin!', '/user/user_info_page.php');
		}elseif ($value > $rec_ac_cash) {
			response_message2rediect('You dont have enough cash!', '/user/user_info_page.php');
		} else {
			//db
			$db_conn = db_connect('root','root');
			$new_cash = $rec_ac_cash - $value;
			$result = $db_conn->prepare("UPDATE Tweb_User_Credit
										SET Tweb_User_Credit_Cash = :val
										WHERE Tweb_User_ID = :uid ;");
			$result->bindValue(':val', $new_cash);
			$result->bindValue(':uid', $uid);
			$result->execute();

			//BlockTrail
			send_tran($server_wallet, $receive_address, $request_bitcoin);

			//refresh
			echo '<meta http-equiv="refresh" content="1"/>';

			#header("Refresh:0; url=page2.php");
			echo '<div class="alert alert-success alert-dismissable">
			  		<a href="#" class="close" data-dismiss="alert" aria-label="close" >&times;</a>
			  		<strong>Success!</strong> Cash to Bitcoin Success!
					</div>';
		}
	}


?>