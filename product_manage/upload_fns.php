<?php

	include_once('../Config_db/config_db.php');
	
	function insert_goods($id, $name, $price, $inventory, $image_path, $type, $date, $quantity, $creator, $desc) {
		try {
			$db_conn = db_connect('root', '');
			$stmt = $db_conn->prepare('INSERT INTO tweb_product VALUES(:id, :name, :price, :inventory, :image_path, :type, :date, :quantity, :creator, :desc)');
			
			$stmt->bindparam(':id', $id);
			$stmt->bindparam(':name', $name);
			$stmt->bindparam(':price', $price);
			$stmt->bindparam(':inventory', $inventory);
			$stmt->bindparam(':image_path', $image_path);
			$stmt->bindparam(':type', $type);
			$stmt->bindparam(':date', $date);
			$stmt->bindparam(':quantity', $quantity);
			$stmt->bindparam(':creator', $creator);
			$stmt->bindparam(':desc', $desc);
			
			$stmt->execute();
			return 'Your product has been post successfully';
			
		} catch (PDOException $e) {
			return $e->getMessage();
		}
		
	}
?>