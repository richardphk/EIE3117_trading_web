<?php

	include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
	
	function product_header() {
		?>
			<div class="row">
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
											<th>Refund requests</th>
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
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT * FROM Tweb_Product WHERE Tweb_Product_Creator_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
	
	function get_sales($id) {
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT * FROM Tweb_Order, Tweb_Sale_Record WHERE Tweb_Order.Tweb_Order_Sale_Record_ID = Tweb_Sale_Record.Tweb_Sale_Record_ID AND Tweb_Order.Tweb_Order_Product_ID = "' . $id . '";');
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		return $rec;
	}
        
        function get_refund_status($oid, $uid, $cid, $pid, $quantity) {
            $db_conn = db_connect('root','');
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
                                <form action="<?php echo '../refund/response_refund.php' ?>" method="post">
                                    <input type="hidden" name="approve" value="1" />
                                    <input type="hidden" name="rid" value="<?php echo $rid; ?>" />

                                    <input type="hidden" name="uid" value="<?php echo $uid; ?>" />
                                    <input type="hidden" name="cid" value="<?php echo $cid; ?>" />
                                    <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
                                    <input type="hidden" name="quantity" value="<?php echo $quantity; ?>" />

                                    <input type="submit" class="btn btn-success" value="Approve" />
                                </form>
                                <form action="<?php echo '../refund/response_refund.php'; ?>" method="post">
                                    <input type="hidden" name="approve" value="2" />
                                    <input type="hidden" name="rid" value="<?php echo $rid; ?>" />

                                    <input type="submit" class="btn btn-danger" value="Reject" />
                                </form>
                        <?php
                        break;
                    case '1':
                        return 'Approved';
                        break;
                    case '2':
                        return 'Rejected';
                        break;
                    default:
                        return false;
                }
            }
        }
	
	function product_footer() {
		?>
			</div>
		<?php
	}
?>