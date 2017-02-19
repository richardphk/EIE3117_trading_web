<?php
	require_once('../page_gen.php');
	
	
	
	page_header('Upload');
?>
	<form action="product_manage/upload.php" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
		<div class="form-group">
			<label class="col-sm-2 control-label">Product name:</label>
			<div class="col-sm-10">
				<input class="form-control" id="focusedInput" type="text" name="product_name">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Price:</label>
			<div class="col-sm-10">
				<input class="form-control" id="focusedInput" type="text" name="product_price">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Product inventory:</label>
			<div class="col-sm-10">
				<input class="form-control" id="focusedInput" type="text" name="product_inventory">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Product image:</label>
			<div class="col-sm-10">
				<input class="form-control" id="inputfile" type="file" name="product_image">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Product type:</label>
			<div class="col-sm-10">
				<input class="form-control" id="focusedInput" type="product_type">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Quantity:</label>
			<div class="col-sm-10">
				<input class="form-control" id="focusedInput" type="product_quantity">
			</div>
		</div>
		
		<div class="form-group">
			<label class="col-sm-2 control-label">Description:</label>
			<div class="col-sm-10">
				<textarea class="form-control" rows="3" name="product_desc"></textarea>
			</div>
		</div>
		<input type="submit" class="btn btn-primary btn-lg btn-block" value="Submit" />
		
	</form>
	
	
	
<?php
	page_footer();

?>