<?php

	include_once('../config_db/config_db.php');
	
	
	function get_result($id, $type) {
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT * FROM tweb_product WHERE Tweb_Product_ID = "' . $id . '";');
		
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $value){
			return $value['Tweb_Product_' . $type];
		}
	}
	
	function get_email($id) { 
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT Tweb_User_Email FROM Tweb_user WHERE Tweb_User_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $value) {
			return $value['Tweb_User_Email'];
		}
	}
		
		
	function email_to_buyer($id) { 
			$email = get_email($id);
			$from = "From: support@phpbookmark \r\n";
			$mesg = "Thanks for buying";
			if (mail($email, 'PHPBookmark login information', $mesg, $from)) {
				return true;
		} else { 
			throw new Exception('Could not send email.'); 
		}
	}
	
	function email_to_seller($seller_id, $buyer_id) { 
			$email = get_email($seller_id);
			$from = "From: support@phpbookmark \r\n";
			$mesg = "Thanks for buying";
			if (mail($email, 'PHPBookmark login information', $mesg, $from)) {
				return true;
		} else { 
			throw new Exception('Could not send email.'); 
		}
	}


?>