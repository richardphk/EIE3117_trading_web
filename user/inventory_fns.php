<?php

	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/config_db/config_db.php');
	
	function product_header() {
		?>
			<div class="row")
		<?php
	}
	
	function product_body($id) {
		$products = get_result($id);
		$button = 0;
		$div = 0;
		foreach ($products as $product) {
			?>
				<div class="col-sm-6 col-md-3">
					<div class="thumbnail">
						<img src="<?php echo $product['Tweb_Product_Image_Path'] ?>" width="140" height="140"></img>
						<div class="caption">
							<h4 class="pull-right">$<?php echo $product['Tweb_Product_Price'] ?></h4>
							<h4><?php echo $product['Tweb_Product_Name'] ?></h4>
							<p><?php echo $product['Tweb_Product_Desc'] ?></p>
							<p>
								<button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="collapse" data-target="#demo<?php echo $button += 1; ?>">Sales record</button>
								<div id="demo<?php echo $div += 1; ?>" class="collapse">
									<table class="table">
										<thead>
											<th>Date</th>
											<th>Buyer</th>
											<th>Quantity</th>
										</thead>
										<tbody>
											<?php 
												$sales = get_sales($product['Tweb_Product_ID']); 
												foreach ($sales as $sale) {
											?>
											<tr>
												<td><?php echo $sale['Tweb_Sale_Record_Order_Date'] ?></td>
												<td><?php echo $sale['Tweb_Sale_Record_Customer_ID'] ?></td>
												<td><?php echo $sale['Tweb_Order_Quantity'] ?></td>
											</tr>
											<?php
												}
											?>
										</tbody>
									</table>
								</div>
							</p>
						</div>
					</div>
				</div>
			<?php
		}
	}
	
	function get_result($id) {
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT * FROM tweb_product WHERE Tweb_Product_Creator_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
	
	function get_sales($id) {
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT tweb_sale_record.Tweb_Sale_Record_Order_Date, 
										tweb_sale_record.Tweb_Sale_Record_Customer_ID, 
											tweb_order.Tweb_Order_Quantity 
												FROM tweb_order, tweb_sale_record 
													WHERE tweb_order.Tweb_Order_Sale_Record_ID = tweb_sale_record.Tweb_Sale_Record_ID 
														AND tweb_order.Tweb_Order_Product_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
	
	function product_footer() {
		?>
			</div>
		<?php
	}
?>