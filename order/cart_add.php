<?php
	include_once('cart_fns.php');
	session_start();
	
	$product_id;
	$product_quantity;
	
	if (isset($_GET['product_id'])) {
		$product_id = $_GET['product_id'];
	} else {
		echo "Error";
		exit;
	}
	
	if (isset($_GET['product_quantity'])) {
		$product_quantity = $_GET['product_quantity'];
		
		if (check_inventory($product_id, $product_quantity) == true) {
			$product_quantity = $product_quantity + 1;
			echo $product_id;
			echo $product_quantity . '<~~after check<br />';
		} else {
			echo 'You can not make more';
			exit;
		}
	} else {
		$product_quantity = '1';
	}

	$cart_item = array('product_quantity' => $product_quantity);
	
	if(!isset($_SESSION['cart'])) {
		$_SESSION['cart'] = array();
	}
	
	if(array_key_exists($product_id, $_SESSION['cart'])) {
		
	} else {
		$_SESSION['cart'][$product_id] = $cart_item;
	}
	
?>