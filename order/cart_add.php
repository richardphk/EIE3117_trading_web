<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/order/cart_fns.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/session/checking.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/session/redirect_page.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/page_gen.php');
	require_once('../session/create_session.php');
	
	//start_session(10);
	
	page_header('Adding Cart');
	print_r($_SESSION);
	$product_id;
	$product_quantity;
	
	
	if (check_login()) {
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
		
		response_message2rediect('Added', $_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/order/cart_page.php');
	} else {
		not_loggedin();
		page_footer();
	}
?>