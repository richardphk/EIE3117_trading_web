<?php
	include_once('../config_db/config_db.php');
	
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
			<link rel="stylesheet" href="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">  
			<script src="https://cdn.static.runoob.com/libs/jquery/2.1.1/jquery.min.js"></script>
			<script src="https://cdn.static.runoob.com/libs/bootstrap/3.3.7/js/bootstrap.min.js"></script>
			
			<form id="order_list" action='order/order.php' method="post">
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
		<?php
	}
	
	function cart_table($id, $quantity) {
		?>
		<tr>
			<td>
				<img src="<?php echo get_result($id, 'Image_Path'); ?>" class="img-thumbnail" width="140" height="140" />
				<p name="<?php echo $id; ?>"><?php echo get_result($id, 'Name'); ?></p>
			</td>
			<td name="<?php echo $id; ?>_price" id="<?php echo $id; ?>_price" '>
				$<?php echo get_result($id, 'Price'); ?>
					
			</td>
			<td>
				<select name="<?php echo $id; ?>_quantity" id="<?php echo $id; ?>_quantity" onchange="calculator('<?php echo $id; ?>', <?php echo get_result($id, 'Price'); ?>)">
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
							<p id="total_amount">$0</p>
						</td>
					</tr>
					</tbody>
				</table>
				<input type="submit" class="btn btn-primary btn-lg" value="Purchase" />
			</form>
		<?php
	}
?>