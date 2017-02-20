<?php
	include_once('../config_db/config_db.php');
	
	function table_header() {
		?>
			<h2>Purchase Record</h2>
			<div class="panel-group" id="accordion">
		<?php
	}
	
	function table($user_id) {
		$number = 0;
		$numberr = 0;
		$record = get_record($user_id, 'ID', 'Order_Date');
		foreach ($record as $rec) {
		?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse<? echo $number += 1; ?>">
							<?php echo $rec['Tweb_Sale_Record_ID']; ?>   <?php echo $rec['Tweb_Sale_Record_Order_Date'];?>
						</a>
					</h4>
				</div>
				<div id="collapse<? echo $number += 1; ?>" class="panel-collapse collapse in">
					<div class="panel-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Product</th>
									<th>Quantity</th>
								</tr>
							</thead>
							<tbody>
								
									<?php $details = get_details('Product_ID', 'Quantity', $rec['Tweb_Sale_Record_ID']);
										foreach ($details as $det) {
										?>
										<tr>
											<td><img src="<?php echo get_product($id, 'Image_Path'); ?>" class="img-thumbnail" width="140" height="140" />
												<?php echo get_product($det['Tweb_Order_Product_ID'], 'Name');?></td>
											<td><?php echo $det['Tweb_Order_Quantity'];?></td>
										</tr>
										<?php 
										} 
										?>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php
		}
	}
	
	
	
	function get_product($id, $type) {
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT * FROM tweb_product WHERE tweb_product_id = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $value){
			return $value['Tweb_Product_' . $type];
		
	}
	}
	
	function get_details($field1, $field2, $id){
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT Tweb_Order_' . $field1 . ', Tweb_Order_' . $field2 . ' FROM tweb_order WHERE Tweb_Order_Sale_Record_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
	
	function get_record($user_id, $field1, $field2) {
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT Tweb_Sale_Record_' . $field1 . ', Tweb_Sale_Record_' . $field2 . ' FROM tweb_sale_record WHERE Tweb_Sale_Record_Customer_ID = "' . $user_id . '";');
		
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
	
	function table_footer() {
		?>
			</div>	
		<?php
	}
?>