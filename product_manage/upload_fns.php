<?php

	include_once('../config_db/config_db.php');

	function upload_form() {
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
						<input class="form-control" id="focusedInput" type="text" name="product_price" />
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Quantity:</label>
					<div class="col-sm-10">
						<input class="form-control" id="focusedInput" type="text" name="product_quantity" />
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Product image:</label>
					<div class="col-sm-10">
						<input class="form-control" id="inputfile" type="file" name="product_image" accept="image/*" />
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Product type:</label>
					<div class="col-sm-10">
						<select class="form-control" name="product_type">
							<option>Router</option>
							<option>Laptop</option>
							<option>Printer</option>
							<option>Monitor</option>
						</select>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-2 control-label">Description:</label>
					<div class="col-sm-10">
						<textarea class="form-control" rows="3" name="product_desc" ></textarea>
					</div>
				</div>
				<input type="submit" class="btn btn-primary btn-lg" value="Submit" />
				
			</form>
		<?php
	}
	
	function insert_goods($id, $name, $price, $quantity, $image_path, $type, $date, $sale, $creator, $desc) {
		try {
			$db_conn = db_connect('root', '');
			$stmt = $db_conn->prepare('INSERT INTO tweb_product VALUES(:id, :name, :price, :quantity, :image_path, :type, :date, :sale, :creator, :desc)');
			
			$stmt->bindparam(':id', $id);
			$stmt->bindparam(':name', $name);
			$stmt->bindparam(':price', $price);
			$stmt->bindparam(':quantity', $quantity);
			$stmt->bindparam(':image_path', $image_path);
			$stmt->bindparam(':type', $type);
			$stmt->bindparam(':date', $date);
			$stmt->bindparam(':sale', $sale);
			$stmt->bindparam(':creator', $creator);
			$stmt->bindparam(':desc', $desc);
			
			$stmt->execute();
			return 'Your product has been post successfully';
			
		} catch (PDOException $e) {
			return $e->getMessage();
		}
	}
?>