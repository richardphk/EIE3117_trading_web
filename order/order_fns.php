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
	
	function email_to_seller($seller_id, $buyer_id) { 
			$email = get_user_info($seller_id, 'Tweb_User_Email');
			$buyer_name = get_user_info($seller_id, 'Tweb_User_Name');
			$from = "From: support@phpbookmark \r\n";
			$mesg = $buyer_id . " has bought something from you";
			if (mail($email, 'Online Trading Website', $mesg, $from)) {
				return true;
		} else { 
			throw new Exception('Could not send email.'); 
		}
	}
	
	function sales_record($id) {
		$date = date('Y-m-d');
		$record_id = 'test01'; //Temporary data
		try {
			$db_conn = db_connect('root', '');
			$stmt = $db_conn->prepare('INSERT INTO tweb_sale_record (Tweb_Sale_Record_ID, Tweb_Sale_Record_Customer_ID, Tweb_Sale_Record_Order_Date) VALUES (:record_id, :id, :date)');
			
			$stmt->bindparam(':record_id', $record_id);
			$stmt->bindparam(':id', $id);
			$stmt->bindparam(':date', $date);
			
			$stmt->execute();
			return 'Your sales record has been added <br />';
			
		} catch (PDOException $e) {
			return $e->getMessage();
		}
		
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