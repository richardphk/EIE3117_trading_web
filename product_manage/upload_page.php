<?php
	require_once('../page_gen.php');
	
	
	
	page_header('Upload');
?>
	<form action="product_manage/upload.php" method="post" enctype="multipart/form-data">
		Product name: 
		<input type="text" name="product_name" /> <br />
		Price: 
		$ <input type="text" name="product_price" size="4" /> <br />
		Inventory: 
		<input type="inventory" name="product_inventory" /> <br />
		Product Image: 
		<input type="file" name="product_image" id="product_image"/> <br />
		Product type: 
		<input type="text" name="product_type" /> <br />
		Quantity: 
		<input type="text" name="product_quantity" /> <br />
		Description
		<input type="text" name="product_desc" width="100" height="100"/> <br />
		<input type="submit" value="Submit" name="submit" /> 
		
	</form>
	
	
	
<?php
	page_footer();

?>