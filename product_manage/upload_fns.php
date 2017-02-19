<?php

	include_once('../config_db/config_db.php');
	
	function insert_goods($id, $name, $price, $quantity, $image_path, $type, $date, $creator, $desc) {
		try {
			$db_conn = db_connect('root', '');
			$stmt = $db_conn->prepare('INSERT INTO tweb_product(Tweb_Product_ID, Tweb_Product_Name, Tweb_Product_Price, Tweb_Product_Inventory, Tweb_Product_Image_Path, Tweb_Product_Type, Tweb_Product_Create_Date, Tweb_Product_Creator_ID, Tweb_Product_Desc) VALUES(:id, :name, :price, :quantity, :image_path, :type, :date, :creator, :desc)');
			
			$stmt->bindparam(':id', $id);
			$stmt->bindparam(':name', $name);
			$stmt->bindparam(':price', $price);
			$stmt->bindparam(':quantity', $quantity);
			$stmt->bindparam(':image_path', $image_path);
			$stmt->bindparam(':type', $type);
			$stmt->bindparam(':date', $date);
			$stmt->bindparam(':creator', $creator);
			$stmt->bindparam(':desc', $desc);
			
			$stmt->execute();
			return 'Your product has been post successfully';
			
		} catch (PDOException $e) {
			return $e->getMessage();
		}
		
	}
?>