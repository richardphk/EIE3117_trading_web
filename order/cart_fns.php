<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
	
    //Function for checking the product inventory
    function check_inventory($id, $quantity) {
		
        $db_conn = db_connect('root','root');
        $result = $db_conn->prepare('SELECT Tweb_Product_Inventory FROM Tweb_Product WHERE Tweb_Product_ID = "' . $id . '";');
        $result->execute();
        $rec = $result->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($rec as $value){
            $db_quantity = $value['Tweb_Product_' . $type];
            if ($quantity <= $db_quantity) {
                return true;
            } else {
                return false;
            }
        }
    }
	
    //Functions for getting product information
    function get_result($id, $type) {
        $db_conn = db_connect('root','root');
        $result = $db_conn->prepare('SELECT * FROM Tweb_Product WHERE Tweb_Product_id = "' . $id . '";');
        $result->execute();
        $rec = $result->fetchAll(PDO::FETCH_ASSOC);
        foreach($rec as $value){
            return $value['Tweb_Product_' . $type];
        }
    }
	
    //Function for displaying the Cart
    function cart_header() {
?>
        <h2>Cart</h2>
            <form id="order_list" action='order/order.php' method="post">
		<table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th></th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
			</tr>
                    </thead>
	
                    <tbody>
<?php
	}
	
    function cart_table($id, $quantity) {
?>
                        <tr>
                            <input type="hidden" name="product_id[]" value="<?php echo get_result($id, 'ID'); ?>">
                            <input type="hidden" name="product_name[]" value="<?php echo get_result($id, 'Name'); ?>">
                            
                            <td>
                                    <img src="<?php echo get_result($id, 'Image_Path'); ?>" class="img-thumbnail" width="140" height="140" />
                            </td>
                            
                            <td>
                                    <?php echo get_result($id, 'Name') . '<br/>' . get_result($id, 'Desc'); ?>
                            </td>
                            
                            <td id="<?php echo $id; ?>_price" '>
                                    <input type="hidden" name="product_price[]" value="<?php echo get_result($id, 'Price'); ?>">
                                    <?php echo '<p>Credit: ' . '<b style="color:#ff0808">$' . get_result($id, 'Price') . '</b></p>'; ?>
                                    <?php echo '<p>Bitcoin: ' . '<b style="color:#ff0808">$' . get_result($id, 'Price')/1000000 . '</b></p>'; ?>

                            </td>
                            
                            <td>
                                <select name="product_quantity[]" id="<?php echo $id; ?>_quantity" onchange="calculator('<?php echo $id; ?>', <?php echo get_result($id, 'Price'); ?>)">
<?php 
                                echo get_result($id, 'Inventory');
                                for ($i=1; $i<=get_result($id, 'Inventory'); $i++) {
                                    if ($i == $quantity) {
                                        echo '<option value="' . $i . '" selected>' . $i . '</option>';
                                    } else {
                                        echo '<option value="' . $i .'">' . $i . '</option>';
                                    }
                                }
?>

                                </select>
                            </td>
                            
                            <td>
                                <p>Credit: $<b id="<?php echo $id;?>_total_credit" style="color:#ff0808"></b></p>
                                <p>Bitcoin: $<b id="<?php echo $id;?>_total_bitcoin" style="color:#ff0808"></b></p>
                            </td>	
                            
                    </tr>
                    <script>calculator('<?php echo $id; ?>', <?php echo get_result($id, 'Price'); ?>);</script>
                    <script>get_id('<?php echo $id; ?>');</script>
                    <script>get_price(<?php echo get_result($id, 'Price'); ?>);</script>
<?php
	}
	
    function cart_footer() {
?>
                    <tr>
                        <td />
                        <td />
                        <td />
			<td>Total Amount<br /></td>
			<td rowspan="2">
                            <p>Credit: $<b id="total_amount_credit" style="color:#ff0808"><script>total_amount();</script></b></p>
                            <p>Bitcoin: $<b id="total_amount_bitcoin" style="color:#ff0808"><script>total_amount();</script></b></p>
			</td>
                    </tr>
                    
                    <tr>
                        <td />
                        <td />
                        <td />
                        <td>
                            <input type="submit" class="btn btn-primary btn-lg" value="Purchase" />
                        </td>
                    </tr>
		</tbody>
            </table>
                                
				
	</form>
<?php
	}
?>