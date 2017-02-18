<?php

	include_once('./order_fns.php');
	include_once('../page_gen.php');
	session_start();
	
	//$product_id = $isset($_GET['product_id']) ? $_GET['id'] : "";
	$product_id = 'ABCD';

	page_header('cart');
?>
	
	<style type="text/css">
    .Table
    {
        display: table;
    }
    .Title
    {
        display: table-caption;
        text-align: center;
        font-weight: bold;
        font-size: larger;
    }
    .Heading
    {
        display: table-row;
        font-weight: bold;
        text-align: center;
    }
    .Row
    {
        display: table-row;
    }
    .Cell
    {
        display: table-cell;
        border: solid;
        border-width: thin;
        padding-left: 5px;
        padding-right: 5px;
    }
	</style>

	<form action='order/order.php' method="post">
		<div class="order_list">
			<div class="Heading">
				<div class="Cell">
					<p>Item</p>
				</div>
				<div class="Cell">
					<p>Price</p>
				</div>
				<div class="Cell">
					<p>Quantity</p>
				</div>
				<div class="Cell">
					<p>Total Price</p>
				</div>
			</div>
			
			<div class="Row">
				<input name="product_id" type="hidden" value="<?php echo $product_id?>" />
				<div class="Cell">
					<p><?php echo '<img src="' . get_result($product_id, 'Image_Path') . '" height="100" width="100" />';?></p>
					<p><?php echo get_result($product_id, 'Name'); ?></p>
				</div>
				<div class="Cell">
					<p>$<?php echo get_result($product_id, 'Price'); ?></p>
				</div>
				<div class="Cell">
					<select name="quantity" id="quantity"><?php echo get_result($product_id, 'Sale');
												for ($i=0; $i<=get_result($product_id, 'Sale'); $i++) {
													echo '<option value = "' . $i . '">' . $i . '</option>';
												}
												?></select>
				</div>
			</div>
		</div>
		<input type="submit" value="Purchase" />
	</form>
			

<?php
	page_footer();
?>