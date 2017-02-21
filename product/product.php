<?php
	include_once('../Config_db/config_db.php');

	function product_frame_header(){
		echo'<div class="row">';
	
	}

	function product_sale_form($img,$name,$des,$price,$PID){
		
		echo'
			  <div class="col-sm-6 col-md-4" style="padding-right:0px;">
				<div class="thumbnail">
				<form action= "order/cart_add.php" method="GET">
				  <img src="'. $img .'" alt="'.$name.'" style="width:200px;">
				  <div class="caption">
					<h3>'. $name .'</h3>
					<p>'. $des .'</p>
					<p align="right">$'. $price .'</p>
					<p align="right"><a href="#" class="btn btn-primary" role="button">Buy</a>
					<input type="hidden" name="product_id" value="'.$PID.'"></input>
					<button class="btn btn-default" role="button" onClick="this.form.submit()">add Cart<span class="glyphicon glyphicon-shopping-cart"></span></button>
					</p>
				  </div>
				 </form>
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
		print_r($key);
		//print $key_type;
		if($key_type == 'all'){
			//echo"type-search";
			if(empty($key['type'])){

				$key['type'] = null;
				print($key['type']);
			}
			if(empty($key['price'])){
				$stat_price = "(select * FROM `tweb_product` where Tweb_Product_Price between ? and ?) as a";
				$key_price = array(100,10000);
			}
			else{
				$key_price = explode(",", $key['price']);
				
				$stat_price = "(select * FROM `tweb_product` where Tweb_Product_Price between ? and ?) as a";
			}
			
			if(empty($key['name'])){
				if(!empty($key['search'])){
					$key['name'] = $key['search'];
				}else{
					$key['name'] = ' ';
				}
				
			}
			$key_where_stat = 'Tweb_Product_Type = ?';
			$search_key_num = count($key['type']);
			$stat_type = 'SELECT * FROM `tweb_product` as c where c.Tweb_Product_Type = ?;';
			$stat_select = 'SELECT * FROM ';
			$stat_form = '(SELECT * FROM tweb_product';
			$stat_add = ' or ';
			//print($search_key_num . $key['type']);
			switch ($search_key_num) {
				case 0:
					$stat_form = $stat_form .') as c ';
					break;
				case 1: 
					$stat_form = $stat_form . ' where ' . $key_where_stat . ') as c ';
					break;
				case 2:
					$stat_form = $stat_form . ' where ' . $key_where_stat . $stat_add . $key_where_stat . ') as c ';
					break;
				case 3:
					$stat_form = $stat_form . ' where ' . $key_where_stat . $stat_add . $key_where_stat . $stat_add . $key_where_stat . ') as c ';
					break;
				case 4:
					$stat_form = $stat_form . ' where ' . $key_where_stat . $stat_add . $key_where_stat 
								. $stat_add . $key_where_stat . $stat_add . $key_where_stat . ') as c  ';
					break;
			}

			//echo $stat_type;
			$stat_form .= 'where a.Tweb_Product_ID = b.Tweb_Product_ID and a.Tweb_Product_ID = c.Tweb_Product_ID and b.Tweb_Product_ID = c.Tweb_Product_ID ';
			
			
			
			
			$stat_keyword = "(select * from tweb_product where Tweb_Product_Name like ? or Tweb_Product_Desc like ?) as b";
			$key_keyword = '%'.$key['name'].'%';
			
			$total_stat = $stat_select . $stat_price . ',' . $stat_keyword . ',' . $stat_form;
			if(!empty($key['sort'])){
				$sort = $key['sort'];
				if($sort == 'Lowest Price'){
					$total_stat .= "ORDER BY `a`.`Tweb_Product_Price` ASC";
				}
				elseif($sort == 'Hightest Price'){
					$total_stat .= "ORDER BY `a`.`Tweb_Product_Price` DESC";
				}
				
			}
			//echo $total_stat;
			$db_conn = db_connect('root','');
			$result = $db_conn->prepare($total_stat);
			$type_array_bind = array(
								1 => $key_price[0],
								2 => $key_price[1],
								3 => $key_keyword,
								4 => $key_keyword,
								);
			//print_r($type_array_bind);
			
			for($n=1; $n<=$search_key_num; $n++){
				if($search_key_num == 0 or empty($key['type'])){
					array_push($type_array_bind,'');
				}
				else{
					array_push($type_array_bind,$key['type'][$n-1]);
				}
			}
			//print_r($type_array_bind);
			for($r=1;$r<=count($type_array_bind); $r++){
				
				$result->bindParam($r,$type_array_bind[$r]);
			}
			$result->execute();

			/*for($n=1; $n<=$search_key_num; $n++){
				$result->bindParam($n,$key['type'][($n-1)]);
			}*/
		}
		elseif($key_type == "keyword"){
			$db_conn = db_connect('root','');
			$stat_keyword = "(select * from tweb_product where Tweb_Product_Name like ? or Tweb_Product_Desc like ?) as b";
			$result = $db_conn->prepare($stat);
			$key_2 = '%'.$key.'%';
			$result->bindParam(1,$key_2);
			$result->bindParam(2,$key_2);
			//var_dump($result);
		}
		else{
			
			$db_conn = db_connect('root','');
			$stat = "(select * FROM `tweb_product` where Tweb_Product_Price between ? and ?)";
			$result = $db_conn->prepare($stat);
			//print_r("price:". $key);
			$key = explode(",", $key['price']);
			
			$result->bindParam(1,$key[0]);
			$result->bindParam(2,$key[1]);
		}
		
		
		
		
		
		//$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		//print_r($rec);
		if(count($rec)==0){
			
			//$price_check = explode(",", $key['price']);
			if(is_array($key['price'])){
				//print_r($key['price']);
				$key_2 = "price $";
				$n = 0;
				foreach($key as $r){
					if($n >=1){
						$key_2 .= $r;
						break;
					}
					$key_2 .= $r . " - $" ;
					$n++;
				}
				echo "<h2 align=\"center\" style=\"font-family: 'Lora', serif;\">No matches for '". $key_2 . "'</h2>";
			}
			else{
				//echo "<h2 align=\"center\" style=\"font-family: 'Lora', serif;\">No matches for '". $key['search'] . "'</h2>";
				echo "<h2 align=\"center\" style=\"font-family: 'Lora', serif;\">No Result. </h2>";
			}
			
		}
		$num = 0;
		product_frame_header();
		//print_r($rec);
		foreach($rec as $item){
			//print_r($item);
			product_sale_form($item['Tweb_Product_Image_Path'],$item['Tweb_Product_Name'],$item['Tweb_Product_Desc'],$item['Tweb_Product_Price'],$item['Tweb_Product_ID']);
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
