<?php
	include_once('../config_db/config_db.php');
	include_once('order_fns.php');
	include_once('../page_gen.php');
	session_start();
	
	page_header('Order');

	//unset($_SESSION['cart'])Cancel session
	//Add record
	//Add order
	//Send email

	echo "Thanks for using our service." . '<br .>';

	order_table_header();
	$total_price = 0;

	for ($i=0; $i < count($_POST['product_id']); $i++) {
		order_table_body($i, $_POST['product_name'][$i], $_POST['product_price'][$i], $_POST['product_quantity'][$i]);
		$total_price += $_POST['product_price'][$i] * $_POST['product_quantity'][$i];
	}
	order_table_footer($total_price);

	/*
	
	
	//Buyer ID from session $buyer_id = $_SESSION['valid_user'];
	$product_id = $_POST["product_id"];
	$seller_id = get_result($product_id, 'Creator_ID');
	
	email_to_buyer($seller_id);
	echo sales_record($seller_id);
	
	echo 'The email record has been sent to your email. <br />';
	email_to_seller($seller_id, $seller_id);
	
	echo 'Thanks for purchasing <br />';
	*/
?>