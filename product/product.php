<?php
	include_once('../Config_db/config_db.php');
	function product_frame_header(){
		echo'<div class="row">';

	}

	function product_sale_form($img,$name,$des,$price){
		
		echo'
			  <div class="col-sm-6 col-md-4">
				<div class="thumbnail">
				  <img src="'. $img .'" alt="'.$name.'">
				  <div class="caption">
					<h3>'. $name .'</h3>
					<p>'. $des .'</p>
					<p>'. $price .'</p>
					<p align="right"><a href="#" class="btn btn-primary" role="button">Buy</a> 
					<a href="#" class="btn btn-default" role="button">add Cart<span class="glyphicon glyphicon-shopping-cart"></span></a></p>
				  </div>
				</div>
			  </div>
			';
	}
	function product_frame_end(){
		echo'</div>';
	}
	function get_type(){
		$stat = 'select Tweb_Product_Type FROM `tweb_product`;';
		$db_conn = db_connect('root','');
		$result = $db_conn->prepare($stat);
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		echo'<div class="checkbox">';
		//print_r($rec);
		foreach($rec as $type){
			
			echo'
			  <label>
				<input type="checkbox" value="'.$type['Tweb_Product_Type'].'" id="'.$type['Tweb_Product_Type'].'"  name="type[]" onChange="this.form.submit()">
				'.$type['Tweb_Product_Type'].'
				</input>
			  </label>';
		}
		echo'</div>';
	}
	
	function get_result($key,$key_type){
		//print_r($key);
		if($key_type != 'keyword'){
			$search_key_num = count($key);
			$stat = 'SELECT `Tweb_Product_Name`,`Tweb_Product_Price`,`Tweb_Product_Inventory`,`Tweb_Product_Image_Path`,
										`Tweb_Product_Type`,`Tweb_Product_Desc`,`Tweb_Product_Create_Date`,`Tweb_Product_Sale` 
											FROM `tweb_product` where ';
			
			$stat_add = ' or ';
			for($n=0; $n<$search_key_num; $n++){
				//echo $n;
				if($n == $search_key_num-1){
					$stat = $stat .$key_type . ' = '. '?' . ';';
				}
				else{
					$stat = $stat . $key_type . ' = '. '?' . $stat_add;
				}
				
			}
			// echo $stat;
			$db_conn = db_connect('root','');
			$result = $db_conn->prepare($stat);
			for($n=1; $n<=$search_key_num; $n++){
				$result->bindParam($n,$key[($n-1)]);
			}
		}
		else{
			$db_conn = db_connect('root','');
			$stat = "select `Tweb_Product_Name`,`Tweb_Product_Price`,`Tweb_Product_Inventory`,`Tweb_Product_Image_Path`,
										`Tweb_Product_Type`,`Tweb_Product_Desc`,`Tweb_Product_Create_Date`,`Tweb_Product_Sale` 
										FROM `tweb_product` where Tweb_Product_Name like ? or Tweb_Product_Desc like ?;";
			$result = $db_conn->prepare($stat);
			$key_2 = '%'.$key.'%';
			$result->bindParam(1,$key_2);
			$result->bindParam(2,$key_2);
			//var_dump($result);
		}
		
		
		
		
		
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		if(count($rec)==0){
			echo "<h2 align=\"center\" style=\"font-family: 'Lora', serif;\">No matches for '". $key . "'</h2>";
		}
		$num = 0;
		product_frame_header();
		//print_r($rec);
		foreach($rec as $item){
			//print_r($item);
			product_sale_form($item['Tweb_Product_Image_Path'],$item['Tweb_Product_Name'],$item['Tweb_Product_Desc'],$item['Tweb_Product_Type']);
			$num +=1;
			$check = $num/3;
			//print($check);
			if(is_int($check)){
				
				product_frame_end();
				product_frame_header();
			}

			//echo '<p/>';
		}
		product_frame_end();
		
	}
	//print_r($rec);
	


	

?>
