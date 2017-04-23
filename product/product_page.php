
<?php
	include_once('../page_gen.php');
	include_once('product.php');
	page_header("product");
        
        $token = md5(uniqid());
        $_SESSION['purchase_token'] = $token;
        session_write_close();
        
	function price_check($format,$input){
		$input = htmlspecialchars($input);
		$regex = $format;
		if(preg_match($regex, $input)){
			return $input;
		} else {
			return "1,500000";
		}

	}
?>

<script>
	
  	$(function() {
	  	var price = new Slider("#slider", {
		  	range: true,
		});

		var check_price_setted = "<?php if(!empty(htmlspecialchars($_GET['price'])))
										{
											echo price_check('^[0-9],[0-9]^',$_GET['price']);

										}else echo''; ?>";
		//document.write(check_price_setted);

	  	if(check_price_setted.length != 0){
		  	var price_new = check_price_setted;
		  	//alert(typeof(price_new),price_new);
		 	price_new_2 = price_new.split(',');
		  	//document.write(price_new_2[0],typeof(price_new_2));
		 	//$("#slider").attr('data-slider-min', 200);
		 	var price_new_2 = [parseInt(price_new_2[0],10),parseInt(price_new_2[1],10)];
		 	//document.write(arr,typeof(arr[0]));
		  	//$("#slider").attr('range', arr);
		 	price.setValue([price_new_2[0],price_new_2[1]],true,false);
	  	}

		
		//price.setValue(500);

		var value = price.getValue();
		$("#amount").val( "$" + value[0] + " - $" + value[1] );
		price.on("slide", function(slideEvt){
			$("#amount").val( "$" + slideEvt[0] + " - $" + slideEvt[1]);
		})
		price.on("slideStop",function(slideEvt)	{
			//var value = price.getValue();
			//$("#amount").val( "$" + value[0] + " - $" + value[1] );
			$("#amount").val( "$" + slideEvt[0] + " - $" + slideEvt[1]);
			
			$("#adv_search_form").submit();
			var php = "<?php if(!empty(htmlspecialchars($_GET['price'])))
										{
											echo price_check('^[0-9],[0-9]^',$_GET['price']);

										}else echo''; ?>";
			
			//document.write(php);
			//document.write(php);

			var new_val = php.split(",");
			
		})
		
	}
  );
</script>
	<div class="row" style="width:inherit;">
		<div class="col-md-2" style="background-color:white;height:100%;padding-right: 0px;">
			<form id="adv_search_form" name="search" class="form-control" action="" method="GET" style="height:100%;">
				
				<label>Type:</label>
				<?php get_type()?>
				<input type="Reset" name="Reset" value="Reset"/></br></br>
				
					<label>Price range:</label>
					<input name="" type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;width:90px;" >
				<br>
				<br>
				<input id="slider" type="text" name="price" class="span2" value=""  <?php get_price()?> data-slider-step="100" style="width:100%;"/>
				<br>
				<br>
				
				<label>Keyword:</label>
				<div class="input-group" style="">
				<input type="text" class="form-control check" name="name" placeholder="Keyword..." required>
				<span class="input-group-btn">
						  <button type="submit" class="btn btn-default"  style="height:34px;" action="this.form.submit();">
							<span class="glyphicon glyphicon-search"></span>
						  </button>
					  </span>
				</div>
				<label>Sort by:</label>
				<select name="sort" class="form-control">
				  <option id="blank" selected="selected"></option>
				  <option id="Lowest_Price" value="Lowest_Price">Lowest Price</option>
				  <option id="Hightest_Price" value="Hightest_Price">Hightest Price</option>
				  <option id="amount" value="Lastest_Arrival">Lastest Arrival</option>
				  
				</select>
				
			</form>

				
		</div>
			 
		<div class="col-md-10" style="padding:5px;">
			<main>
				<?php
							if($_SERVER['REQUEST_METHOD'] == 'GET'){
								//echo "Orig";
								//print_r($_GET);
								//echo "<p>INARRY<p>";
								
								$inspect_pri_array = array();
								$i = 0;
								//print_r($_GET['type']);
								foreach ($_GET as $field => $in_arry) {
									if(empty($_GET[$field])){
										//print("HI");
									}else{
										if(is_array($_GET[$field])){
											foreach ($_GET[$field] as $n => $V) {
												//print_r($n);
												$_GET[$field][$n] = urlencode($_GET[$field][$n]);
											//print($in_arry);
											}
										}else{
											if($field == 'price'){
												$price_set = explode(",", $_GET[$field]);
												foreach($price_set as $n =>$val){
													$price_set[$n] = htmlspecialchars($price_set[$n]);

												}

												$_GET[$field] = implode(",", $price_set);
												//echo $_GET[$field];
												
											}
					
											$_GET[$field] = urlencode($_GET[$field]);
										}
									}
							}
							//echo"<p>result";
							//print_r($_GET);
							$value2 = $_GET;
							//$value2 = array_replace($key_price,$inspect_pri_array);
			
							get_result($value2,"all", $token);
							//var_dump(!empty($_GET['sort']));
							if(!empty($_GET['sort'])){
								set_selected_sort($_GET['sort']);
							}
							if(empty($_GET['type'])){
								exit;
							}
							set_checked_butt($value2['type']);

						}
					
							function set_checked_butt($value){
								//print_r($value);
								//echo 'HI';
								foreach($value as $id){
									$type_id = str_replace(' ','',$id);
									//echo'<script> $("#Smart Phone").prop("checked", true);</script>';
									echo'<script>
											$("#'.$type_id.'").prop("checked", true);

										</script>';
								}
							}
							function set_selected_sort($value){
									//print($value);
									echo'<script>
												$("#blank").attr("selected", false);
												$("#'.$value .'").attr("selected", true);

											</script>';
							}
					

				?>
			</main>
		</div>
	</div>

<?php
	page_footer();

?>