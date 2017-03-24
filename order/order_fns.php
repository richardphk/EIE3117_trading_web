<?php

	include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
        
	function get_result($id, $type) {
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare('SELECT * FROM Tweb_Product WHERE Tweb_Product_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $value){
			return $value['Tweb_Product_' . $type];
		}
	}
	
	function get_user_info($id, $attribute) { 
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare('SELECT * FROM Tweb_User WHERE Tweb_User_ID = "' . $id . '";');
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
		$db_conn = db_connect('root','root');
		$stmt = $db_conn->prepare('INSERT INTO Tweb_Sale_Record (Tweb_Sale_Record_ID, Tweb_Sale_Record_Customer_ID, Tweb_Sale_Record_Order_Date) VALUES (:purchase_id, :user_id, :purchase_date)');
		$stmt->bindparam(':purchase_id', $purchase_id);
		$stmt->bindparam(':user_id', $user_id);
		$stmt->bindparam(':purchase_date', $purchase_date);
		$stmt->execute();
		//Statement for chick record echo 'Sale record of ' . $purchase_id . ' is updated <br />';
	
	}

	function add_order($order_id, $product_id, $quantity, $sales_id) {
<<<<<<< Updated upstream
		$db_conn = db_connect('root','root');
		$stmt = $db_conn->prepare('INSERT INTO Tweb_Order (Tweb_Order_ID, Tweb_Order_Product_ID, Tweb_Order_Quantity, Tweb_Order_Sale_Record_ID) VALUES (:order_id, :product_id, :quantity, :sales_id)');
=======
                $price = $quantity * get_result($product_id, 'Price');
                
		$db_conn = db_connect('root', '');
		$stmt = $db_conn->prepare('INSERT INTO Tweb_Order (Tweb_Order_ID, Tweb_Order_Product_ID, Tweb_Order_Quantity, Tweb_Order_Price, Tweb_Order_Sale_Record_ID) VALUES (:order_id, :product_id, :quantity, :price, :sales_id)');
>>>>>>> Stashed changes
		$stmt->bindparam(':order_id', $order_id);
		$stmt->bindparam(':product_id', $product_id);
		$stmt->bindparam(':quantity', $quantity);
                $stmt->bindparam(':price', $price);
		$stmt->bindparam(':sales_id', $sales_id);
		$stmt->execute();

		//Statement for chick order echo 'Order of ' . $product_id . ' with ' . $quantity . ' is added <br />';
	}

	function edit_inventory($id, $quantity) {
		$inventory = get_result($id, 'Inventory');
		$inventory = $inventory - $quantity;

		$sale = get_result($id, 'Sale');
		$sale = $sale + $quantity;

		$db_conn = db_connect('root','root');
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
        
        function check_amont($uid, $pid, $price, $quantity) {
            
            $total_price = 0;
            $user_credit = get_user_credit($uid, 'Tweb_User_Credit_Cash');
            
            for ($i=0; $i < count($_POST['product_id']); $i++) {
                $total_price += $price[$i] * $quantity[$i];
            }
                    
            if ($user_credit >= $total_price)
                return true;
            else
                return false;
            
        }
        
        
        function get_user_credit($id, $field) {
            $db_conn = db_connect('root','root');
            $result = $db_conn->prepare('SELECT * FROM Tweb_User_Credit WHERE Tweb_User_ID = "' . $id . '";');
            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach($rec as $value){
                return $value[$field];
            }
        }
        
        function transaction($sid, $uid, $price) {
            try {
                
		$db_conn = db_connect('root','root');
		$stmt = $db_conn->prepare('UPDATE Tweb_User_Credit SET Tweb_User_Credit_Cash = ' . (get_user_credit($uid, 'Tweb_User_Credit_Cash')-$price) .' WHERE Tweb_User_ID = :uid');
		$stmt->bindparam(':uid', $uid);
		$stmt->execute();
                
                
                $stmt = $db_conn->prepare('UPDATE Tweb_User_Credit SET Tweb_User_Credit_Cash = ' . (get_user_credit($sid, 'Tweb_User_Credit_Cash')+$price) .' WHERE Tweb_User_ID = :sid');
		$stmt->bindparam(':sid', $sid);
		$stmt->execute();
                
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        
        function add_payment_record($pid, $sid, $amount, $date, $uid) {
            try {
                $refund = 0;
                $upid = get_user_credit($uid, 'Tweb_User_Credit_id');
                
                $db_conn = db_connect('root','root');
                
                $stmt = $db_conn->prepare('INSERT INTO Tweb_Payment (Tweb_Payment_ID, Tweb_Payment_Sale_Record_ID, Tweb_Payment_Payment_Amount, Tweb_Payment_Payment_Date, Tweb_Payment_Refund, Tweb_Payment_Buyer_Credit_ID) VALUES (:pid, :sid, :amount, :date, :refund, :upid)');
                $stmt->bindparam(':pid', $pid);
                $stmt->bindparam(':sid', $sid);
                $stmt->bindparam(':amount', $amount);
                $stmt->bindparam(':date', $date);
                $stmt->bindparam(':refund', $refund);
                $stmt->bindparam(':upid', $upid);
		$stmt->execute();
                
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
        
        function add_amount($sid, $amount) {
            try {
                
                $db_conn = db_connect('root', '');
                
                $stmt = $db_conn->prepare('UPDATE Tweb_Sale_Record SET Tweb_Sale_Record_Amount = :amount WHERE Tweb_Sale_Record_ID = :sid');
                $stmt->bindparam(':sid', $sid);
                $stmt->bindparam(':amount', $amount);
                $stmt->execute();
                
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
        }
?>