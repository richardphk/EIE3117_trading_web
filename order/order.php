<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/order/order_fns.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/gen_id.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/includes/get_today.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/session/redirect_page.php');


    page_header('Order');
	
    //Check whether the user has logged in
    if (check_login()) {
        $token = $_SESSION['purchase_token'];
        unset($_SESSION['purchase_token']);
        if($token && $_POST['token'] == $token) {
            //Check whether the user's credit is enough to pay the total amount
            if(check_amont($_SESSION['login_user_id'], $_POST['product_id'], $_POST['product_price'], $_POST['product_quantity'])){
                //Clear the cart record
                unset($_SESSION['cart']);

                //Add sale records
                $purchase_id = gen_id('Tweb_Sale_Record');
                $purchase_date = get_today();
                $payment_type = 'Credit';

                add_sale_record($purchase_id, $_SESSION['login_user_id'], $purchase_date, $payment_type);

                $amount = 0;

                //Add order
                for ($i=0; $i < count($_POST['product_id']); $i++) {
                    $order_id = gen_id('Tweb_Order');
                    $total_price = $_POST['product_price'][$i] * $_POST['product_quantity'][$i];
                    add_order($order_id, $_POST['product_id'][$i], $_POST['product_quantity'][$i], $purchase_id, $total_price, $payment_type);
                    edit_inventory($_POST['product_id'][$i], $_POST['product_quantity'][$i]);

                    $amount += $total_price;

                    $seller_id = get_result($_POST['product_id'][$i], 'Creator_ID');
                    //email_to_seller($seller_id, $_SESSION['login_user_id'], $_POST['product_id'][$i], $_POST['product_name'][$i], $_POST['product_quantity'][$i]);

                    transaction_credit($seller_id, $_SESSION['login_user_id'], $_POST['product_price'][$i] * $_POST['product_quantity'][$i]);
                }

                    add_amount($purchase_id, $amount);
                    $payment_id = gen_id('Tweb_Payment');

                    add_payment_record($payment_id, $purchase_id, $amount, $purchase_date, $_SESSION['login_user_id'], $payment_type);

                    //email_to_buyer($_SESSION['login_user_id']);
                    echo '<meta http-equiv="refresh" content="0; url=/order/order_result.php?sid=' . $purchase_id . '&type=' . $payment_type . '" />';

                  //If the user's credit is not enough, the user's payment for paying the total amount will be automatically changed to bitcoin
                } elseif (check_bitcoin_amount($_SESSION['login_user_id'], $_SESSION['login_user_pw'], $_POST['product_id'], $_POST['product_price'], $_POST['product_quantity'])){
                    unset($_SESSION['cart']);

                    //Add sale record
                    $purchase_id = gen_id('Tweb_Sale_Record');
                    $purchase_date = get_today();
                    $payment_type = 'Bitcoin';

                    add_sale_record($purchase_id, $_SESSION['login_user_id'], $purchase_date, $payment_type);

                    $amount = 0;

                    //Add order
                    for ($i=0; $i < count($_POST['product_id']); $i++) {

                        $order_id = gen_id('Tweb_Order');
                        $total_price = ($_POST['product_price'][$i]/1000000) * $_POST['product_quantity'][$i];
                        add_order($order_id, $_POST['product_id'][$i], $_POST['product_quantity'][$i], $purchase_id, $total_price, $payment_type);

                        edit_inventory($_POST['product_id'][$i], $_POST['product_quantity'][$i]);

                        transaction_bitcoin($_SESSION['login_user_id'], $_SESSION['login_user_pw'], $total_price, $_POST['product_id'][$i]);

                        $amount += $total_price;

                        $seller_id = get_result($_POST['product_id'][$i], 'Creator_ID');
                        //email_to_seller($seller_id, $_SESSION['login_user_id'], $_POST['product_id'][$i], $_POST['product_name'][$i], $_POST['product_quantity'][$i]);
                    }

                    add_amount($purchase_id, $amount);
                    $payment_id = gen_id('Tweb_Payment');
                    add_payment_record($payment_id, $purchase_id, $amount, $purchase_date, $_SESSION['login_user_id'], $payment_type);

                    //email_to_buyer($_SESSION['login_user_id']);
                    echo '<meta http-equiv="refresh" content="0; url=/order/order_result.php?sid=' . $purchase_id . '&type=' . $payment_type . '" />';
                }
        } else {
            ?>

			<div class="jumbotron">
				<h1>Illegal access.</h1>
			</div>

		<?php
        }
    }else {
        not_loggedin();
    }
    page_footer();

?>