<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/config_db/config_db.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/tBTC/tBTC_fns.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/includes/gen_id.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
	require_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');

	start_session();

	$uid = $_SESSION['login_user_id'];
	$upw = $_SESSION['login_user_pw'];

	create_wallet_account($uid,$upw,$client);

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
			response_message2rediect('Thank you for using bitcoin!', './user_info_page.php');
		} else {
			response_message2rediect('You already have a account!', '../home.php');
		}
	}

?>