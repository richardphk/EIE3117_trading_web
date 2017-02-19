<?php

	include_once('./order_fns.php');
	include_once('../page_gen.php');
	session_start();
	
	//$product_id = $isset($_GET['product_id']) ? $_GET['id'] : "";
	$product_id = 'ABCD';

	page_header('cart');
	
?>
	

	<script>
		function calculator(price) {
			var total_price;
			var quantity = document.getElementById("quantity").value;
			
			total_price = price * quantity;
			
			document.getElementById("total_price").innerHTML="$" + total_price;
		}
	</script>
	<link rel="stylesheet" href="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">  
	<script src="https://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<form id="order_list" action='order/order.php' method="post">
		<input name="product_id" type="hidden" value="<?php echo $product_id?>" />
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Item</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Total Price</th>
				</tr>
			</thead>
			
			<tbody>
				<tr>
					<td>
						<?php echo '<img src="' . get_result($product_id, 'Image_Path') . '" class="img-responsive" alt="Cinque Terre" />';?>
						<?php echo get_result($product_id, 'Name'); ?>
					</td>
					<td id="price">
						$<?php echo get_result($product_id, 'Price'); ?>
					
					</td>
					<td>
						<select name="quantity" id="quantity" onchange="calculator(<?php echo get_result($product_id, 'Price'); ?>)">
							<?php echo get_result($product_id, 'Sale');
								for ($i=0; $i<=get_result($product_id, 'Sale'); $i++) {
									echo '<option value = "' . $i . '">' . $i . '</option>';
								}
							?>
						</select>
					</td>
					<td>
						<p id="total_price">$0</p>
					</td>
					
					<tfoot>
						<td rowspan="2">
							<input type="submit" class="btn btn-primary btn-lg btn-block" value="Purchase" />
						</td>
					</tfoot>
				</tr>
			</tbody>
			
		</table>
	</form>
			

<?php
	page_footer();
?>