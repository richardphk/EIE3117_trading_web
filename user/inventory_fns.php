<?php

	include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
	
	function product_header() {
		?>
			<div class="row">
		<?php
	}
	
        function product_refund($uid) {
            $result = check_refund($uid);
            if ($result) {
                echo '';
            }
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
											<th>Status</th>
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
												<td><?php echo get_refund_status($sale['Tweb_Order_ID'], $_SESSION['login_user_id'], $sale['Tweb_Sale_Record_Customer_ID'], $product['Tweb_Product_ID'], $sale['Tweb_Order_Quantity']); ?></td>
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
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare('SELECT * FROM Tweb_Product WHERE Tweb_Product_Creator_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
	
	function get_sales($id) {
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare('SELECT * FROM Tweb_Order, Tweb_Sale_Record WHERE Tweb_Order.Tweb_Order_Sale_Record_ID = Tweb_Sale_Record.Tweb_Sale_Record_ID AND Tweb_Order.Tweb_Order_Product_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
        
        function get_refund_status($oid, $uid, $cid, $pid, $quantity) {
            $db_conn = db_connect('root','root');
            $result = $db_conn->prepare('SELECT * FROM Tweb_Refund WHERE Tweb_Refund_Order_ID = "' . $oid . '";');
            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            if ($rec) {
                foreach ($rec as $r) {
                    $rid = $r['Tweb_Refund_ID'];
                    $status = $r['Tweb_Refund_Approve'];
                }
                switch ($status) {
                    case '0':
                        ?>
                        <button class="btn btn-success" data-toggle="modal" data-target="#<?php echo $rid . '_approve'; ?>">Approve</button>
                        <div class="modal fade" id="<?php echo $rid . '_approve'; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title" id="myModalLabel">Warning</h4>
                                    </div>
                                    <div class="modal-body">Are you sure to approve the refund?</div>
                                    <div class="modal-footer">
                                        <form action="<?php echo '../refund/response_refund.php' ?>" method="post">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <input type="hidden" name="approve" value="1" />
                                            <input type="hidden" name="rid" value="<?php echo $rid; ?>" />

                                            <input type="hidden" name="uid" value="<?php echo $uid; ?>" />
                                            <input type="hidden" name="cid" value="<?php echo $cid; ?>" />
                                            <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
                                            <input type="hidden" name="oid" value="<?php echo $oid; ?>" />
                                            <input type="hidden" name="quantity" value="<?php echo $quantity; ?>" />

                                            <input type="submit" class="btn btn-danger" value="Yes" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button class="btn btn-danger" data-toggle="modal" data-target="#<?php echo $rid . '_reject'; ?>">Reject</button>
                        <div class="modal fade" id="<?php echo $rid . '_reject'; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                        <h4 class="modal-title" id="myModalLabel">Warning</h4>
                                    </div>
                                    <div class="modal-body">Are you sure to reject the refund?</div>
                                    <div class="modal-footer">
                                        <form action="<?php echo '../refund/response_refund.php'; ?>" method="post">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <input type="hidden" name="approve" value="2" />
                                            <input type="hidden" name="rid" value="<?php echo $rid; ?>" />

                                            <input type="submit" class="btn btn-danger" value="Reject" />
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                                
                        <?php
                        break;
                    case '1':
                        return '<button type="button" class="btn btn-warning">Refuned</button>';
                        break;
                    case '2':
                        return '<button type="button" class="btn btn-danger">Sold</button>';
                        break;
                    default:
                        return false;
                }
            } else {
                return '<button type="button" class="btn btn-success">Sold</button>';
                
            }
        }
        
        function check_refund($uid) {

            $db_conn = db_connect('root','root');
            $result = $db_conn->prepare('SELECT * FROM tweb_refund WHERE tweb_refund_order_id IN 
                (SELECT tweb_order_id FROM tweb_order WHERE tweb_order_product_id IN 
                    (SELECT tweb_product_id FROM tweb_product WHERE tweb_product_creator_id = "' . $uid . '")) 
                        AND tweb_refund_approve = "0";');
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