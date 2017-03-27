<?php

    include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
	
    function product_refund($uid) {
        $result = check_refund($uid, '0');
        if ($result) {
            ?>
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                            <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#refundList">
                                            Refund List
                                    </a>
                            </h4>
                    </div>
                    <div id="refundList" class="panel-collapse">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sale Record ID</th>
                                    <th>Sale Date</th>
                                    <th>Request User</th>
                                    <th>Request Product</th>
                                    <th>Request Quantity</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
            <?php
            foreach ($result as $record) {
            ?>
                                <tr>
                                    <td><?php echo $record['Tweb_Refund_Sale_Record_ID']; ?></td>
                                    <td><?php echo $record['Tweb_Sale_Record_Order_Date']; ?></td>
                                    <td><?php echo $record['Tweb_User_Name']; ?></td>
                                    <td><?php echo $record['Tweb_Product_Name'] . '<br /> <img src="' . $record['Tweb_Product_Image_Path'] . '" width="140" height ="140" /> <br />' . $record['Tweb_Product_Desc'] ; ?></td>
                                    <td><?php echo $record['Tweb_Order_Quantity']; ?></td>
                                    <td>
                                        <button class="btn btn-success" data-toggle="modal" data-target="#<?php echo $record['Tweb_Refund_ID'] . '_approve'; ?>">Approve</button>
                                        <div class="modal fade" id="<?php echo $record['Tweb_Refund_ID'] . '_approve'; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                            <input type="hidden" name="rid" value="<?php echo $record['Tweb_Refund_ID']; ?>" />

                                                            <input type="hidden" name="uid" value="<?php echo $uid; ?>" />
                                                            <input type="hidden" name="cid" value="<?php echo $record['Tweb_User_ID']; ?>" />
                                                            <input type="hidden" name="pid" value="<?php echo $record['Tweb_Product_ID']; ?>" />
                                                            <input type="hidden" name="oid" value="<?php echo $record['Tweb_Refund_Order_ID']; ?>" />
                                                            <input type="hidden" name="quantity" value="<?php echo $record['Tweb_Order_Quantity']; ?>" />

                                                            <input type="submit" class="btn btn-danger" value="Yes" />
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button class="btn btn-danger" data-toggle="modal" data-target="#<?php echo $record['Tweb_Refund_ID'] . '_reject'; ?>">Reject</button>
                                        <div class="modal fade" id="<?php echo $record['Tweb_Refund_ID'] . '_reject'; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                            <input type="hidden" name="rid" value="<?php echo $record['Tweb_Refund_ID']; ?>" />

                                                            <input type="submit" class="btn btn-danger" value="Reject" />
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                <?php
            }
            ?>
                                </div>
                            </div>
                        </tbody>
                    </table>
                </div>
            <?php
        }
    }

	function product_body($id) {
		$products = get_result($id);
		$button = 0;
		$div = 0;
		foreach ($products as $product) {
			?>
				<div class="col-sm-6 col-md-4">
					<div class="thumbnail">
						<img width="140" height="140" src="<?php echo $product['Tweb_Product_Image_Path']; ?>"></img>
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
        
        function product_refunded_list($uid) {
            $result = check_refund($uid, '1');
            //Further development  
            
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
                        return '<button type="button" class="btn btn-danger" disabled>Requesting Refund</button>';
                        break;
                    case '1':
                        return '<button type="button" class="btn btn-warning" disabled>Refuned</button>';
                        break;
                    case '2':
                        return '<button type="button" class="btn btn-danger" disabled>Sold</button>';
                        break;
                    default:
                        return false;
                }
            } else {
                return '<button type="button" class="btn btn-success" disabled>Sold</button>';
            }
        }

        function check_refund($uid, $status) {
            $db_conn = db_connect('root','root');
            /*$check_refund_sql = 'SELECT * FROM Tweb_Refund WHERE Tweb_Refund_Order_ID IN
                (SELECT Tweb_Order_ID FROM Tweb_Order WHERE Tweb_Order_Product_ID IN
                    (SELECT Tweb_Product_ID FROM Tweb_Product WHERE Tweb_Product_Creator_ID = "' . $uid . '"))
                        AND Tweb_Refund_Approve = "0";';*/
            
            $check_refund_sql = 'SELECT Tweb_Refund_ID, Tweb_Refund_Sale_Record_ID, Tweb_Refund_Payment_ID, Tweb_Refund_Order_ID, Tweb_Refund_Approve, 
                    Tweb_User_ID, Tweb_User_Name,
                    Tweb_Product_ID, Tweb_Product_Name, Tweb_Product_Image_Path, Tweb_Product_Desc,
                    Tweb_Order_Quantity, 
                    Tweb_Sale_Record_Order_Date
             FROM Tweb_Refund, Tweb_User, Tweb_Product, Tweb_Order, Tweb_Sale_Record
             WHERE Tweb_Refund.Tweb_Refund_Sale_Record_ID = Tweb_Sale_Record.Tweb_Sale_Record_ID
                    AND Tweb_Refund.Tweb_Refund_Order_ID = Tweb_Order.Tweb_Order_ID
                    AND Tweb_Sale_Record.Tweb_Sale_Record_ID = Tweb_Order.Tweb_Order_Sale_Record_ID
                    AND Tweb_Sale_Record.Tweb_Sale_Record_Customer_ID = Tweb_User.Tweb_User_ID
                    AND Tweb_Order.Tweb_Order_Product_ID = Tweb_Product.Tweb_Product_ID
			AND Tweb_Product.Tweb_Product_Creator_ID = "' . $uid .'"
			AND Tweb_Refund.Tweb_Refund_Approve = "' . $status . '";';
            
            $result = $db_conn->prepare($check_refund_sql);
            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            return $rec;
        }

?>