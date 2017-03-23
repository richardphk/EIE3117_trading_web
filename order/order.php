<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/order/order_fns.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/gen_id.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/get_today.php');


	page_header('Order');
	
	if (check_login()) {
            if(check_amont($_SESSION['login_user_id'], $_POST['product_id'], $_POST['product_price'], $_POST['product_quantity'])){
                    unset($_SESSION['cart']);

                    //Add record
                    $purchase_id = gen_id('Tweb_Sale_Record');
                    $purchase_date = get_today();

                    echo add_sale_record($purchase_id, $_SESSION['login_user_id'], $purchase_date);

                    order_table_header();
                    $total_price = 0;


                    //Add order
                    for ($i=0; $i < count($_POST['product_id']); $i++) {
                            $order_id = gen_id('Tweb_Order');
                            $payment_id = gen_id('Tweb_Payment');

                            add_order($order_id, $_POST['product_id'][$i], $_POST['product_quantity'][$i], $purchase_id);
                            edit_inventory($_POST['product_id'][$i], $_POST['product_quantity'][$i]);

                            order_table_body($i, $_POST['product_name'][$i], $_POST['product_price'][$i], $_POST['product_quantity'][$i]);
                            $total_price += $_POST['product_price'][$i] * $_POST['product_quantity'][$i];

                            $seller_id = get_result($_POST['product_id'][$i], 'Creator_ID');
                            email_to_seller($seller_id, $_SESSION['login_user_id'], $_POST['product_id'][$i], $_POST['product_name'][$i], $_POST['product_quantity'][$i]);
                            
                            transaction($seller_id, $_SESSION['login_user_id'], $_POST['product_price'][$i] * $_POST['product_quantity'][$i], $purchase_id, $payment_id, $purchase_date);
                    }
                    order_table_footer($total_price);

                    email_to_buyer($_SESSION['login_user_id']);

                    ?>
                            <div class="jumbotron">
                                    <h4>Thank you for using our service. An email record has been sent to you.</h4>
                            </div>

                    <?php
            } else {
                echo "You don't have enough credit";
            } 
	} else {
		not_loggedin();
	}
	page_footer();

?>