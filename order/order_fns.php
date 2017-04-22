<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/tBTC/BlockTrail_API.php');
    include_once($_SERVER['DOCUMENT_ROOT'] . '/tBTC/tBTC_fns.php');

    //Function for getting the product information
    function get_result($id, $type) {
        $db_conn = db_connect('root','root');
        $result = $db_conn->prepare('SELECT * FROM Tweb_Product WHERE Tweb_Product_ID = "' . $id . '";');
        $result->execute();
        $rec = $result->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($rec as $value){
            return $value['Tweb_Product_' . $type];
        }
    }

    //Function for getting the user's information
    function get_user_info($id, $attribute) {
        $db_conn = db_connect('root','root');
        $result = $db_conn->prepare('SELECT * FROM Tweb_User WHERE Tweb_User_ID = "' . $id . '";');
        $result->execute();
        $rec = $result->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($rec as $value) {
            return $value[$attribute];
        }
    }
	
    //Function for sending an email to the buyer
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
	
    //Function for sending an email to the seller
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
	
    //Function for adding sale record to database
    function add_sale_record($purchase_id, $user_id, $purchase_date, $payment_type) {
        $db_conn = db_connect('root','root');
        $stmt = $db_conn->prepare('INSERT INTO Tweb_Sale_Record (Tweb_Sale_Record_ID, Tweb_Sale_Record_Customer_ID, Tweb_Sale_Record_Order_Date, Tweb_Sale_Record_Payment_Type) VALUES (:purchase_id, :user_id, :purchase_date, :payment_type)');
        $stmt->bindparam(':purchase_id', $purchase_id);
        $stmt->bindparam(':user_id', $user_id);
        $stmt->bindparam(':purchase_date', $purchase_date);
        $stmt->bindparam(':payment_type', $payment_type);
        $stmt->execute();
    }

    //Function for adding order record to database.
    function add_order($order_id, $product_id, $quantity, $sales_id, $total_price, $payment_type) {
        $db_conn = db_connect('root', 'root');
        
        $stmt = $db_conn->prepare('INSERT INTO Tweb_Order (Tweb_Order_ID, Tweb_Order_Product_ID, Tweb_Order_Quantity, Tweb_Order_Price, Tweb_Order_Sale_Record_ID, Tweb_Order_Payment_Type) VALUES (:order_id, :product_id, :quantity, :total_price, :sales_id, :payment_type)');
        $stmt->bindparam(':order_id', $order_id);
        $stmt->bindparam(':product_id', $product_id);
        $stmt->bindparam(':quantity', $quantity);
        $stmt->bindparam(':total_price', $total_price);
        $stmt->bindparam(':sales_id', $sales_id);
        $stmt->bindparam(':payment_type', $payment_type);
        $stmt->execute();

    }

    //Function for changing the inventory
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
		
    }

    //Functions for displaying the order table
    function order_table_header($sid, $type) {
        $result = get_sale_record($sid);
?>
			<table class="table">
			    <thead>
                        <tr>
                            <th>Date: <?php echo $result['Tweb_Sale_Record_Order_Date']; ?></th>
                            <th>Payment type: <?php echo $type; ?></th>
                            <th />
                            <td>Receipt number: <?php echo $result['Tweb_Sale_Record_ID']; ?></th>
                        </tr>
			    		<tr>
			      		 	<th>Quantity</th>
			       			<th>Details</th>
			        		<th>Price</th>
                                                <th>Amount</th>
			      		</tr>
			    </thead>
				<tbody>
		<?php
	}

    function order_table_body($sid) {
            $result = get_order_result($sid);
            $amount = 0;
            foreach ($result as $row) {
                $amount += $row['Tweb_Order_Price'];
		?>
                    <tr>
                        <td><?php echo $row['Tweb_Order_Quantity']; ?></td>
                        <td><?php echo $row['Tweb_Product_Name']; ?></td>
                        <td>$<?php echo $row['Tweb_Order_Price']/$row['Tweb_Order_Quantity']; ?></td>
                        <td>$<?php echo $row['Tweb_Order_Price']; ?></td>
                    </tr>
		<?php
            }

            ?>
                    <tr>
                        <td />
                        <td />
                        <td>
                            <p align="right">Total:</p>
                        </td>
                        <td>$<?php echo $amount; ?></td>
                    </tr>

            <?php
    }

    function order_table_footer() {
		?>
				</tbody>
			</table>
                        <div class="jumbotron">
                            <h4>Thank you for using our service. An email record has been sent to you.</h4>
                        </div>
		<?php
    }
        
    //Functino for checking the amount and credit cash
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
        
    //Function for getting the user's credit information
    function get_user_credit($id, $field) {
            $db_conn = db_connect('root','root');
            $result = $db_conn->prepare('SELECT * FROM Tweb_User_Credit WHERE Tweb_User_ID = "' . $id . '";');
            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach($rec as $value){
                return $value[$field];
            }
    }
        
    //Function for doing the credit transaction
    function transaction_credit($sid, $uid, $price) {
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
        
    //Function for adding a payment record
    function add_payment_record($pid, $sid, $amount, $date, $uid, $payment_type) {
            try {
                $refund = 0;
                $upid = get_user_credit($uid, 'Tweb_User_Credit_id');
                
                $db_conn = db_connect('root','root');
                
                $stmt = $db_conn->prepare('INSERT INTO Tweb_Payment (Tweb_Payment_ID, Tweb_Payment_Sale_Record_ID, Tweb_Payment_Payment_Amount, Tweb_Payment_Payment_Date, Tweb_Payment_Refund, Tweb_Payment_Buyer_Credit_ID, Tweb_Payment_Payment_Type) VALUES (:pid, :sid, :amount, :date, :refund, :upid, :payment_type)');
                $stmt->bindparam(':pid', $pid);
                $stmt->bindparam(':sid', $sid);
                $stmt->bindparam(':amount', $amount);
                $stmt->bindparam(':date', $date);
                $stmt->bindparam(':refund', $refund);
                $stmt->bindparam(':upid', $upid);
                $stmt->bindparam(':payment_type', $payment_type);
		$stmt->execute();
                
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
    }
        
    //Function for adding amount to database
    function add_amount($sid, $amount) {
            try {
                
                $db_conn = db_connect('root', 'root');
                
                $stmt = $db_conn->prepare('UPDATE Tweb_Sale_Record SET Tweb_Sale_Record_Amount = :amount WHERE Tweb_Sale_Record_ID = :sid');
                $stmt->bindparam(':sid', $sid);
                $stmt->bindparam(':amount', $amount);
                $stmt->execute();
                
            } catch (Exception $ex) {
                echo $ex->getMessage();
            }
    }
        
    //Function for getting the order result
    function get_order_result($sid) {
            
            $db_conn = db_connect('root','root');
            
            $result = $db_conn->prepare('SELECT Tweb_Order_Price, Tweb_Order_Quantity, 
                                                Tweb_Product_Name
                                            FROM Tweb_Order, Tweb_Product
                                            WHERE Tweb_Order.Tweb_Order_Product_ID = Tweb_Product.Tweb_Product_ID
                                            AND Tweb_Order_Sale_Record_ID = "' . $sid . '";');
            
            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            return $rec;
    }
        
    //Function for getting the sale record
    function get_sale_record($sid) {
            $db_conn = db_connect('root','root');
            
            $result = $db_conn->prepare('SELECT * FROM Tweb_Sale_Record
                                            WHERE Tweb_Sale_Record_ID = "' . $sid . '";');
            
            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($rec as $r) {
                return $r;
            }
    }

    //Function for checking the total amount and bitcoin
    function check_bitcoin_amount($uid, $upw, $pid, $price, $qty) {

        	global $client;

        	$wallet = init_wallet($uid, $upw, $client);
			$balance = wallet_balance($wallet);

        	$total_price = 0;
            $user_credit = get_user_credit($uid, 'Tweb_User_Credit_Cash');
            
            for ($i=0; $i < count($_POST['product_id']); $i++) {
                $total_price += $price[$i] * $qty[$i];
            }

            

            if ($balance[0] >= ($total_price/1000000))
                return true;
            else
                return false;

    }

    //Function for doing the bitcoin transaction.
    function transaction_bitcoin($uid, $upw, $price, $pid) {
        	global $client;
        	$seller_address = get_user_credit(get_result($pid, 'Creator_ID'), 'Tweb_User_Bitcon_RevAddress');
        	$wallet = init_wallet($uid, $upw, $client);
        	send_tran($wallet, $seller_address, $price);
    }
?>