<?php

	include_once('../config_db/config_db.php');
	
	
	function get_result($id, $type) {
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT * FROM tweb_product WHERE Tweb_Product_ID = "' . $id . '";');
		
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $value){
			return $value['Tweb_Product_' . $type];
		}
	}
	
	function get_user_info($id, $attribute) { 
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT * FROM Tweb_user WHERE Tweb_User_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $value) {
			return $value[$attribute];
		}
	}
	
		
	function email_to_buyer($id) { 
			$email = get_user_info($id, 'Tweb_User_Email');
			$from = "From: support@phpbookmark \r\n";
			$mesg = "Thanks for buying";
			if (mail($email, 'Online Trading Website', $mesg, $from)) {
				return true;
		} else { 
			throw new Exception('Could not send email.'); 
		}
	}
	
	function email_to_seller($seller_id, $buyer_id, $product_id, $product_name, $product_quantity) { 
			$email = get_user_info($seller_id, 'Tweb_User_Email');
			$buyer_name = get_user_info($buyer_id, 'Tweb_User_Name');
			$from = "From: support@phpbookmark \r\n";
			$mesg = "This is to notify that user " . $buyer_name . " has bought " . $product_quantity . ' ' . $product_name . '(' . $product_id . ')' . "from you";
			if (mail($email, 'Online Trading Website', $mesg, $from)) {
				return true;
		} else { 
			throw new Exception('Could not send email.'); 
		}
	}
	
	function add_sale_record($purchase_id, $user_id, $purchase_date) {
		$db_conn = db_connect('root', '');
		$stmt = $db_conn->prepare('INSERT INTO tweb_sale_record (Tweb_Sale_Record_ID, Tweb_Sale_Record_Customer_ID, Tweb_Sale_Record_Order_Date) VALUES (:purchase_id, :user_id, :purchase_date)');
			
		$stmt->bindparam(':purchase_id', $purchase_id);
		$stmt->bindparam(':user_id', $user_id);
		$stmt->bindparam(':purchase_date', $purchase_date);
			
		$stmt->execute();
		//Statement for chick record echo 'Sale record of ' . $purchase_id . ' is updated <br />';
	
	}

	function add_order($order_id, $product_id, $quantity, $sales_id) {
		$db_conn = db_connect('root', '');
		$stmt = $db_conn->prepare('INSERT INTO Tweb_Order (Tweb_Order_ID, Tweb_Order_Product_ID, Tweb_Order_Quantity, Tweb_Order_Sale_Record_ID) VALUES (:order_id, :product_id, :quantity, :sales_id)');
			
		$stmt->bindparam(':order_id', $order_id);
		$stmt->bindparam(':product_id', $product_id);
		$stmt->bindparam(':quantity', $quantity);
		$stmt->bindparam(':sales_id', $sales_id);
		
		$stmt->execute();

		//Statement for chick order echo 'Order of ' . $product_id . ' with ' . $quantity . ' is added <br />';
	}

	function edit_inventory($id, $quantity) {
		$inventory = get_result($id, 'Inventory');
		$inventory = $inventory - $quantity;

		$sale = get_result($id, 'Sale');
		$sale = $sale + $quantity;

		$db_conn = db_connect('root', '');
		$stmt = $db_conn->prepare('UPDATE Tweb_Product SET Tweb_Product_Inventory = :inventory, Tweb_Product_Sale = :sale WHERE Tweb_Product_ID = :id');
		
		$stmt->bindparam(':inventory', $inventory);
		$stmt->bindparam(':sale', $sale);
		$stmt->bindparam(':id', $id);
		$stmt->execute();

		//Statement for chick inventory echo 'Product inventory of ' . $id . ' is updated <br />';

	}

	function order_table_header() {
		?>
			<table class="table">
			    <thead>
			    		<tr>
			      		 	<th>#</th>
			       			<th>Product Name</th>
			        		<th>Price</th>
			        		<th>Quantity</th>
			      		</tr>
			    </thead>
				<tbody>
		<?php
	}
	function order_table_body($item, $name, $price, $quantity) {
		?>
			<tr>
				<td><?php echo $item + 1; ?></td>
			    <td><?php echo $name; ?></td>
			    <td>$<?php echo $price; ?></td>
			    <td><?php echo $quantity; ?></td>
			</tr>
		<?php
	}
	function order_table_footer($total) {
		?>
				<tr>
					<td>Total Amount</td>
					<td>$<?php echo $total ?></td>
				</tr>
				</tbody>
			</table>
		<?php
	}

?>