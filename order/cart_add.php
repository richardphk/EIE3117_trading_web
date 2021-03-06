<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/order/cart_fns.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/session/redirect_page.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
    require($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');

    
    page_header('Adding Cart');
    $product_id;
    $product_quantity;

    //Check whether the use has logged in
    if (check_login()) {
        if (isset($_GET['product_id'])) {
            $product_id = $_GET['product_id'];
        } else {
            echo "Error";
            exit;
        }
		
        //Check whether the inventory of the product is enough for the user to add
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
		
	response_message2rediect('Added', 'order/cart_page.php');
    } else {
	not_loggedin();
	page_footer();
    }
?>