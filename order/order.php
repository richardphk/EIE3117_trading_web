<?php
	include_once('../config_db/config_db.php');
	include_once('order_fns.php');
	session_start();
	
	
	//Buyer ID from session $buyer_id = $_SESSION['valid_user'];
	$product_id = $_POST["product_id"];
	$seller_id = get_result($product_id, 'Creator_ID');
	
	echo get_email($seller_id);
	email_to_buyer($seller_id);
?>