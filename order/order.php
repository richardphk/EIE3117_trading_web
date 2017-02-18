<?php
	include_once('../config_db/config_db.php');
	include_once('order_fns.php');
	include_once('../page_gen.php');
	session_start();
	
	page_header('Order');
	
	//Buyer ID from session $buyer_id = $_SESSION['valid_user'];
	$product_id = $_POST["product_id"];
	$seller_id = get_result($product_id, 'Creator_ID');
	
	//email_to_buyer($seller_id);
	//email_to_seller($seller_id, $seller_id);
	echo 'Thanks for purchasing <br />'
	echo sales_record($seller_id);
?>