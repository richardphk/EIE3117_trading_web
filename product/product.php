<?php
	include_once('../Config_db/config_db.php');
	
	function product_sale_form($img,$name,$des,$price){
		echo'<div class="row">
			  <div class="col-sm-6 col-md-4">
				<div class="thumbnail">
				  <img src="'. $img .'" alt="'.$name.'">
				  <div class="caption">
					<h3>'. $name .'</h3>
					<p>'. $des .'</p>
					<p>'. $price .'</p>
					<p><a href="#" class="btn btn-primary" role="button">Buy</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
				  </div>
				</div>
			  </div>
			</div>';
	}
	function get_result(){
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare('SELECT `Tweb_Product_Name`,`Tweb_Product_Price`,`Tweb_Product_Inventory`,`Tweb_Product_Image_Path`,
									`Tweb_Product_Type`,`Tweb_Product_Desc`,`Tweb_Product_Create_Date`,`Tweb_Product_Sale` 
										FROM `tweb_product`;');
		
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		foreach($rec as $item){
			//print_r($item);
			product_sale_form($item['Tweb_Product_Image_Path'],$item['Tweb_Product_Name'],$item['Tweb_Product_Desc'],$item['Tweb_Product_Price']);
			//echo '<p/>';
		}
		
	}
	//print_r($rec);
	


	

?>
