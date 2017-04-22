<?php
	include_once('../config_db/config_db.php');

	function product_frame_header(){
		echo'<div class="row" >';
	}

	function product_sale_form($img,$name,$des,$price,$PID){
		$price_bitcon = $price / 1000000;
		echo'
			  <div class="col-sm-5 col-md-4" style="padding-right:0px; margin-right:0px;">
				<div class="thumbnail">
				
				  <img src="'. $img .'" alt="'.$name.'" style="width:180px;">
				  <div class="caption">
					<h3>'. $name .'</h3>
					<p>'. $des .'</p>
					<p align="right">Credit: <b style="color:#ff0808;">$'. $price .'</b></p>
					<p align="right">Bitcon: <b style="color:#ff0808;">$'. $price_bitcon .'</b></p>
                                        

                                        <div style="" align="right">
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#' . $PID . '">Buy</button>
                                            
                                            <form action= "order/cart_add.php" method="GET" style="display:inline;">
                                                <input type="hidden" name="product_id" value="'.$PID.'"></input>
                                                <button class="btn btn-default" role="button" onClick="this.form.submit();">Add Cart<span class="glyphicon glyphicon-shopping-cart"></span></button>
                                            </form>
                                            
										</div>
                                        
                                        <div class="modal fade" id="' . $PID . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h4 class="modal-title" id="myModalLabel">Warning</h4>
                                                        </div>
                                                        <div class="modal-body">Are you sure to buy a ' . $name . ' ?</div>
                                                        <div class="modal-footer">
                                                            <form action="order/order.php" method="post">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <input type="hidden" name="product_id[]" value="' . $PID . '" />
                                                                <input type="hidden" name="product_name[]" value="' . $name . '" />
                                                                <input type="hidden" name="product_price[]" value="' . $price . '" />
                                                                <input type="hidden" name="product_quantity[]" value="1" />
                                                                <input type="submit" class="btn btn-primary" value="OK" />
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
				  </div>
				
				</div>
			  </div>
			';
	}

	function product_frame_end(){
		/**
		 * return end of product page
		 */
		echo'</div>';
	}

	function get_price(){
		/**
		 * return the min price and max price from the existing product in the database
		 * for setting up the price bar 
		*/
		$stat = 'SELECT MIN(`Tweb_Product_Price`) as min_price, Max(`Tweb_Product_Price`) as max_price FROM `Tweb_Product`';
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare($stat);
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		print_r($rec[0]['min_price']);
		echo 'data-slider-min="'.$rec[0]['min_price'].'" data-slider-max="'.$rec[0]['max_price'].'"';
	}

	function get_type(){
		/**
		 * return the existing product type from the product in the database
		 * for setting up the type choice box
		*/
		$stat = 'select distinct(Tweb_Product_Type) FROM `Tweb_Product`;';
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare($stat);
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
		echo'<div class="checkbox">';
		//print_r($rec);
		foreach($rec as $type){
			//var_dump($type);
			$type_id = str_replace(' ','',$type['Tweb_Product_Type']);
			//print($type['Tweb_Product_Type']);
			echo'
			  <label>
				<input type="checkbox" value="'.$type['Tweb_Product_Type'].'" id="'.$type_id.'"  name="type[]" onChange="this.form.submit()">
				'.$type['Tweb_Product_Type'].'
				</input>
			  </label>';
		}
		echo'</div>';
	}

	function get_result($key,$key_type){
		/**
		 * return whole result from product searching function
		 * it accept 2 param, 1. entire GET array,  2. search type: whether the search is filled all search param
		*/
	
		//print_r($key);
		//print $key_type;
		if($key_type == 'all'){


			// ------------------check whether the product type value from GET is empty------------------.

			if(empty($key['type'])){

				$key['type'] = null;
				//print($key['type']);
			}
			else{
				$inspect_array = array();
				if(is_array($key['type'])){
			
				foreach ($key['type'] as $key2 => $value) {
			
					$value = htmlspecialchars($value, ENT_QUOTES);
					array_push($inspect_array,$value);
				}
		
				$key['type'] = array_replace($key['type'],$inspect_array);
			
			}
			else{
				$key['type'] = htmlspecialchars($key['type'], ENT_QUOTES);
			}
		}



			// ------------------check whether the product price from GET is empty------------------

			if(empty($key['price'])){
				$stat_price = "(select * FROM `Tweb_Product` where Tweb_Product_Price between ? and ?) as a";
				$key_price = array(100,5550000);
			}
			else{
				$key_price = explode(",", $key['price']);
				$inspect_pri_array = array();
				foreach ($key_price as $key => $value) {
					$value = urlencode($value);
					array_push($inspect_pri_array,$value);
					print_r($value);
				}
				$key_price = array_replace($key_price,$inspect_pri_array);
				print_r($key_price);
				$stat_price = "(select * FROM `Tweb_Product` where Tweb_Product_Price between ? and ?) as a";
			}



			// ------------------check whether the product name from GET is empty------------------

			if(empty($key['name'])){
				if(!empty($key['search'])){
					$key['search'] = htmlspecialchars($key['search'], ENT_QUOTES);
					$key['name'] = $key['search'];
				}else{
					$key['name'] = '';
				}
			}
			else{
				$key['name'] = htmlspecialchars($key['name'], ENT_QUOTES);

			}



			// ------------------construct a searching sQL query------------------

			$key_where_stat = 'Tweb_Product_Type = ?';
			$search_key_num = count($key['type']);
			$stat_type = 'SELECT * FROM `Tweb_Product` as c where c.Tweb_Product_Type = ?;';
			$stat_select = 'SELECT * FROM ';
			$stat_form = '(SELECT * FROM Tweb_Product';
			$stat_add = ' or ';
			//print_r($key['type']);
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

			//echo $stat_form;
			$stat_form .= 'where a.Tweb_Product_ID = b.Tweb_Product_ID and a.Tweb_Product_ID = c.Tweb_Product_ID and b.Tweb_Product_ID = c.Tweb_Product_ID ';


			$stat_keyword = "(select * from Tweb_Product where Tweb_Product_Name like ? or Tweb_Product_Desc like ?) as b";
			$key_keyword = '%'.$key['name'].'%';

			$total_stat = $stat_select . $stat_price . ',' . $stat_keyword . ',' . $stat_form;



			//------------------check whether the 'sort' param is empty------------------

			//print_r($key['sort']);
			if(!empty($key['sort'])){
				//print_r($key['sort']);
				$sort = $key['sort'];
				if($sort == 'Lowest_Price'){
					$total_stat .= "ORDER BY `a`.`Tweb_Product_Price` ASC";
					//echo $total_stat;
				}
				elseif($sort == 'Hightest_Price'){
		
					$total_stat .= "ORDER BY `a`.`Tweb_Product_Price` DESC";
					//echo $total_stat;
				}
		
			}
			//------------------connect DB & bind param to SQL query------------------

			//print($total_stat);
			//echo $total_stat;
			$db_conn = db_connect('root','root');
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
					array_push($type_array_bind,'Laptop');
				}
				else{
					array_push($type_array_bind,$key['type'][$n-1]);
				}
			}
			//var_dump($type_array_bind);
			for($r=1;$r<=count($type_array_bind); $r++){
				//print($r);
				$result->bindParam($r,$type_array_bind[$r]);
			}
			$result->execute();
		}


		//------------------separate the SQL query result------------------

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
