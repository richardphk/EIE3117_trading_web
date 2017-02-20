<?php
	include_once('order_fns.php');
	include_once('../page_gen.php');
	include_once('../includes/gen_id.php');
	include_once('../includes/get_today.php');

	session_start();

	page_header('Order');

	//Add record
	$purchase_id = gen_id('Tweb_Sale_Record');
	$dummy_userid = "U00001";
	$purchase_date = get_today();

	echo add_sale_record($purchase_id, $dummy_userid, $purchase_date);
	
	

	//unset($_SESSION['cart'])Cancel session
	//Send email

	echo "Thanks for using our service." . '<br .>';

	order_table_header();
	$total_price = 0;


	//Add order
	for ($i=0; $i < count($_POST['product_id']); $i++) {
		$order_id = gen_id('Tweb_Order');
		//orderid, productid, quantity, salesid

		add_order($order_id, $_POST['product_id'][$i], $_POST['product_quantity'][$i], $purchase_id);
		edit_inventory($_POST['product_id'][$i], $_POST['product_quantity'][$i]);

		order_table_body($i, $_POST['product_name'][$i], $_POST['product_price'][$i], $_POST['product_quantity'][$i]);
		$total_price += $_POST['product_price'][$i] * $_POST['product_quantity'][$i];

		$seller_id = get_result($_POST['product_id'][$i], 'Creator_ID');
		email_to_seller($seller_id, $dummy_userid, $_POST['product_id'][$i], $_POST['product_name'][$i], $_POST['product_quantity'][$i]);
	}
	order_table_footer($total_price);

	
	
	//Buyer ID from session $buyer_id = $_SESSION['valid_user'];
	$product_id = $_POST["product_id"];
	
	email_to_buyer($seller_id);
	echo sales_record($seller_id);
	
	echo 'The email record has been sent to your email. <br />';
	email_to_seller($seller_id, $seller_id);
	
	echo 'Thanks for purchasing <br />';

?>