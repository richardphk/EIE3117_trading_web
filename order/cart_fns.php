<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/config_db/config_db.php');
	
	function check_inventory($id, $quantity) {
		
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT Tweb_Product_Inventory FROM tweb_product WHERE Tweb_Product_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $value){
			$db_quantity = $value['Tweb_Product_' . $type];
			if ($quantity <= $db_quantity) {
				return true;
			} else {
				return false;
			}
		}
		
	}
	
	function get_result($id, $type) {
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT * FROM tweb_product WHERE tweb_product_id = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $value){
			return $value['Tweb_Product_' . $type];
		}
	}
	
	
	function cart_header() {
		?>
			<h2>Cart</h2>
			<form id="order_list" action='order/order.php' method="post">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Item</th>
							<th></th>
							<th>Price</th>
							<th>Quantity</th>
							<th>Total Price</th>
						</tr>
					</thead>
	
				<tbody>
		<?php
	}
	
	function cart_table($id, $quantity) {
		?>
		<tr>
			<input type="hidden" name="product_id[]" value="<?php echo get_result($id, 'ID'); ?>">
			<input type="hidden" name="product_name[]" value="<?php echo get_result($id, 'Name'); ?>">
			<td>
				<img src="<?php echo get_result($id, 'Image_Path'); ?>" class="img-thumbnail" width="140" height="140" />
			</td>
			<td>
				<?php echo get_result($id, 'Name') . '<br/>' . get_result($id, 'Desc'); ?>
			</td>
			<td id="<?php echo $id; ?>_price" '>
				<input type="hidden" name="product_price[]" value="<?php echo get_result($id, 'Price'); ?>">
				$<?php echo get_result($id, 'Price'); ?>
					
			</td>
			<td>
				<select name="product_quantity[]" id="<?php echo $id; ?>_quantity" onchange="calculator('<?php echo $id; ?>', <?php echo get_result($id, 'Price'); ?>)">
					<?php echo get_result($id, 'Inventory');
						for ($i=1; $i<=get_result($id, 'Inventory'); $i++) {
							if ($i == $quantity) {
								echo '<option value="' . $i . '" selected>' . $i . '</option>';
							} else {
								echo '<option value="' . $i .'">' . $i . '</option>';
							}
						}
					?>
					
				</select>
			</td>
			<td>
				<p id="<?php echo $id;?>_total" ">$0</p>
			</td>	
		</tr>
		<script>calculator('<?php echo $id; ?>', <?php echo get_result($id, 'Price'); ?>);</script>
		<script>get_id('<?php echo $id; ?>');</script>
		<script>get_price(<?php echo get_result($id, 'Price'); ?>);</script>
		<?php
	}
	
	function cart_footer() {
		?>
					<tr>
						<td>
							Total Amount:
						</td>
						<td rowspan="2">
							<p id="total_amount"><script>total_amount();</script></p>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="submit" class="btn btn-primary btn-lg" value="Purchase" />
			</form>
		<?php
	}
?>