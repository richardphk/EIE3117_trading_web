<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/order/order_fns.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/gen_id.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/get_today.php');
        include_once($_SERVER['DOCUMENT_ROOT'] . '/session/redirect_page.php');


	page_header('Order');
	
	if (check_login()) {
            if(check_amont($_SESSION['login_user_id'], $_POST['product_id'], $_POST['product_price'], $_POST['product_quantity'])){
                    unset($_SESSION['cart']);

                    //Add record
                    $purchase_id = gen_id('Tweb_Sale_Record');
                    $purchase_date = get_today();

                    add_sale_record($purchase_id, $_SESSION['login_user_id'], $purchase_date);
                    
                    $amount = 0;
                    
                    //Add order
                    for ($i=0; $i < count($_POST['product_id']); $i++) {
                            
                            $order_id = gen_id('Tweb_Order');

                            add_order($order_id, $_POST['product_id'][$i], $_POST['product_quantity'][$i], $purchase_id);
                            edit_inventory($_POST['product_id'][$i], $_POST['product_quantity'][$i]);
                            
                            $amount += $_POST['product_price'][$i] * $_POST['product_quantity'][$i];

                            $seller_id = get_result($_POST['product_id'][$i], 'Creator_ID');
                            //email_to_seller($seller_id, $_SESSION['login_user_id'], $_POST['product_id'][$i], $_POST['product_name'][$i], $_POST['product_quantity'][$i]);
                            
                            transaction($seller_id, $_SESSION['login_user_id'], $_POST['product_price'][$i] * $_POST['product_quantity'][$i]);
                    }
                    
                    
                    add_amount($purchase_id, $amount);
                    $payment_id = gen_id('Tweb_Payment');
                    add_payment_record($payment_id, $purchase_id, $amount, $purchase_date, $_SESSION['login_user_id']);
                    
                    //email_to_buyer($_SESSION['login_user_id']);
                    //response_message2rediect('Purchased successfully!', './order/order_result.php?sid=' . $purchase_id);
                    echo '<meta http-equiv="refresh" content="0; url=./order/order_result.php?sid=' . $purchase_id . '" />';
            } else {
                //Further development for bitcoin transaction
                echo "You don't have enough credit";
            } 
	} else {
		not_loggedin();
	}
	page_footer();

?>