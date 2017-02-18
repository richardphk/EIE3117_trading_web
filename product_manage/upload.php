<?php
	
	require_once('../page_gen.php');
	include_once('upload_fns.php');
	
	page_header('Upload');
	
	/*echo $_FILES['product_image']['name'] . '<br/>';
	echo $_FILES['product_image']['type'] . '<br/>';
	echo $_FILES['product_image']['size'] . '<br/>';
	echo $_FILES['product_image']['tmp_name'] . '<br/>';*/
	
	$upfile = '';
	
	if (isset($_FILES['product_image'])) {
		$upfile = 'product_image/' . basename($_FILES['product_image']['name']);
	}
	//echo $upfile;
	
	//echo '<img src="' . $upfile . '" />';

	
	
	$product_id = 'ABC';
	$product_name = $_POST['product_name']; //string
	$product_price = $_POST['product_price']; //price
	$product_inventory = $_POST['product_inventory']; //string
	$product_type = $_POST['product_type']; //dropdownlist
	$date = date('Y-m-d'); //date
	$product_quantity = $_POST['product_quantity']; //dropdownlist
	$product_creator = 'U00001';//$_SESSION['login_user']; //userid
	$product_desc = $_POST['product_desc']; //String
	
	echo insert_goods($product_id, $product_name, $product_price, $product_inventory, $upfile, $product_type, $date, $product_quantity, $product_creator, $product_desc);
	
	
	page_footer();
	
?>