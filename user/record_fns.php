<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
	
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
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $number += 1; ?>" style="text-decoration:none;">
							<button type="button" class="btn btn-primary btn-lg btn-block"><?php echo 'Recept number: ' . $rec['Tweb_Sale_Record_ID']; ?>   <?php echo 'Date: ' . $rec['Tweb_Sale_Record_Order_Date'];?></button>
						</a>
					</h4>
				</div>
				<div id="collapse<?php echo $numberr += 1; ?>" class="panel-collapse collapse in">
					<div class="panel-body">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Product</th>
									<th></th>
									<th>Type</th>
									<th>Quantity</th>
                                                                        <th>Total Price</th>
                                                                        <th>Refund</th>
								</tr>
							</thead>
							<tbody>
								
									<?php 
                                                                                $details = get_details($rec['Tweb_Sale_Record_ID']);
										$total_price = 0;
										
										foreach ($details as $det) {
											?>
											<tr>
												<td><img src="<?php echo get_product($det['Tweb_Order_Product_ID'], 'Image_Path'); ?>" class="img-thumbnail" width="140" height="140" /></td>
												<td><?php echo get_product($det['Tweb_Order_Product_ID'], 'Name') . '<br />' . get_product($det['Tweb_Order_Product_ID'], 'Desc');?></td>
												<td><?php echo get_product($det['Tweb_Order_Product_ID'], 'Type'); ?></td>
												<td><?php echo $det['Tweb_Order_Quantity']; ?></td>
                                                                                                <td><?php echo '$' . $det['Tweb_Order_Price']; ?></Ftd>
                                                                                                
                                                                                                <td>
                                                                                                    <?php get_refund_status($det['Tweb_Order_ID'], $rec['Tweb_Sale_Record_ID']); ?>
                                                                                                </td>
											</tr>
											<?php 
										} 
										?>
									<tr>
                                                                            <td />
                                                                            <td />
                                                                            <td />
                                                                            <td />
                                                                            <td>
                                                                            <p>Total amount: </p>
                                                                            <p>Payment type: </p>
                                                                            </td>
                                                                            <td><p>$<?php echo get_sale($rec['Tweb_Sale_Record_ID'], 'Amount'); ?></p>
                                                                            <p>
                                                                            	<?php echo get_sale($rec['Tweb_Sale_Record_ID'], 'Payment_Type'); ?>
                                                                            </p>
                                                                            </td>
                                                                                
                                                                        </tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php
		}
	}
	
	
	
	function get_product($id, $type) {
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare('SELECT * FROM Tweb_Product WHERE Tweb_Product_id = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $value){
			return $value['Tweb_Product_' . $type];
		
            }
	}
        
        function get_sale($id, $field) {
            $db_conn = db_connect('root','root');
            $result = $db_conn->prepare('SELECT * FROM Tweb_Sale_Record WHERE Tweb_Sale_Record_ID = "' . $id . '";');
            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach($rec as $r) {
                return $r['Tweb_Sale_Record_' . $field];
            }
        }
	
	function get_details($id){
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare('SELECT * FROM Tweb_Order WHERE Tweb_Order_Sale_Record_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
	
	function get_record($user_id, $field1, $field2) {
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare('SELECT Tweb_Sale_Record_' . $field1 . ', Tweb_Sale_Record_' . $field2 . ' FROM Tweb_Sale_Record WHERE Tweb_Sale_Record_Customer_ID = "' . $user_id . '";');
		
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
        
        function get_payment_id($sid) {
            $db_conn = db_connect('root','root');
            $result = $db_conn->prepare('SELECT * FROM Tweb_Payment WHERE Tweb_Payment_Sale_Record_ID = "' . $sid . '";');
		
            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rec as $r) {
                return $r['Tweb_Payment_ID'];
            }
        }
        
        function get_refund_status($oid, $sid) {
            $db_conn = db_connect('root','root');
            $result = $db_conn->prepare('SELECT * FROM Tweb_Refund WHERE Tweb_Refund_Order_ID = "' . $oid . '";');
            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($rec) {

                foreach ($rec as $r) {
                    $rid = $r['Tweb_Refund_ID'];
                    $status = $r['Tweb_Refund_Approve'];
                }

                switch($status) {
                    case '0':
                        echo '<a href="#" class="btn btn-warning disabled" role="button">Waiting for approval</a>';
                        break;
                    case '1':
                        echo '<a href="#" class="btn btn-success disabled role="button">Refunded</a>';
                        break;
                    case '2':
                        echo '<a href="#" class="btn btn-danger disabled role="button">Refund Rejected</a>';
                        break;
                    default:
                        echo 'Error';
                }
            } else {
                ?>
               
                <button class="btn btn-info" data-toggle="modal" data-target="#<?php echo $oid; ?>">Request</button>
                <div class="modal fade" id="<?php echo $oid; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Warning</h4>
                            </div>
                            <div class="modal-body">Are you sure to request refund?</div>
                            <div class="modal-footer">
                                
                                <form action="<?php echo '../refund/request_refund.php'; ?>" method="post">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <input type="hidden" name="sid" value="<?php echo $sid; ?>" />
                                    <input type="hidden" name="pid" value="<?php echo get_payment_id($sid); ?>" />
                                    <input type="hidden" name="oid" value="<?php echo $oid; ?>" />
                                    <input type="submit" class="btn btn-danger" value="Yes" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>   
                
                <?PHP
            }
        }
	
	function table_footer() {
		?>
			</div>
		<?php
	}
?>