<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
	//require_once($_SERVER['DOCUMENT_ROOT'].'/tBTC/tBTC_fns.php');
	$uid = $_POST['uid'];
	$cid = "222";
	$value = $_POST['Credit'];
	//$Bitconaddr = $_POST['Baddress'];
	//print_r(wallet_balance($Bitconaddr));
	
	/******     this function still not in used         **************/
	function addValue($value,$uid,$cid){
		try{
			$db_conn = db_connect('root','root');
			$result = $db_conn->prepare("UPDATE tweb_user_credit
										SET Tweb_User_Credit_Cash = :val
										WHERE Tweb_User_ID = :uid ;");
			$result->bindValue(':val', $value);
			#$result->bindValue(':cid', $cid);
			$result->bindValue(':uid', $uid);
			$result->execute();
			#$rec = $result->fetchAll(PDO::FETCH_ASSOC);
			#print_r($rec);
		}
		catch(exception $e){
			echo $e;
			
		}
		echo '<div class="alert alert-success alert-dismissable">
			  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			  <strong>Success!</strong> Indicates a successful or positive action.
				</div>';
		*/
		
	}


	#addValue($value,$uid,$cid);



















?>