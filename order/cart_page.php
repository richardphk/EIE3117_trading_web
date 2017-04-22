<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/order/cart_fns.php');
	
	page_header('Cart');
	
        //Check whether the user has logged in
	if (check_login()) {
            
        //A javascript for real-time prices calculation
?>
            <script type="text/javascript">
			
                var item_id = [];
		var item_price = [];
			
		function calculator(id, price) {
					
                    var id_quantity = id + '_quantity';
                    var id_total_credit = id + '_total_credit';
                    var id_total_bitcoin = id + '_total_bitcoin';

                    var quantity = document.getElementById(id_quantity).value;
                    var total_price = price * quantity;

                    document.getElementById(id_total_credit).innerHTML = total_price;
                    document.getElementById(id_total_bitcoin).innerHTML = (total_price/1000000);
                    total_amount();
                    total_amount();
					
					
		}
				
		function get_id(id) {
                    item_id.push(id);
		}
				
		function get_price(price) {
                    item_price.push(price);
                }
				
		function total_amount() {
                    var total_price = 0;
                    for (var i=0; i < item_id.length; i++) {
			var id_quantity = item_id[i] + '_quantity';
			var quantity = document.getElementById(id_quantity).selectedIndex + 1;
			total_price = total_price + (quantity * item_price[i]);
                    }
                    document.getElementById('total_amount_credit').innerHTML = total_price;
                    document.getElementById('total_amount_bitcoin').innerHTML = (total_price/1000000);
                }
            </script>

<?php
        //Check whether the use has added items into the cart
        if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {

            cart_header();

            foreach($_SESSION['cart'] as $id => $value) {
                $quantity = $_SESSION['cart'][$id]['product_quantity'];
                cart_table($id, $quantity);
            }

            cart_footer();
        } else {
        //If the user has not added any items into the cart, it will display a message to the user.
?>
        <div class="jumbotron">
            <h1>You have not added any items into the cart yet.</h1>
        </div>
<?php
        }
    } else {
        not_loggedin();
    }
    
    page_footer();
?>