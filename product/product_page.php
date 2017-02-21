
<?php
	include_once('../page_gen.php');
	include_once('product.php');
	page_header("product");

		

	
?>

<script>
	
	
  $( function() {
	  var price = new Slider("#slider", {
		  range: true,
		  
		  
		});
	var check_price_setted = "<?php if(!empty($_GET['price'])){echo $_GET['price'];}else echo''; ?>";
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
			var php = "<?php if(!empty($_GET['price'])){echo $_GET['price'];} ?>";
			
			//document.write(php);
			//document.write(php);

			var new_val = php.split(",");
			
			
		})
		
		
		
	
	}
  );
</script>
	<div class="row">
		<div class="col-md-2" style="background-color:white;height:100%;padding-right: 0px;">
			<form id="adv_search_form" name="search" class="form-control" action="" method="GET" style="height:100%;">
				
				<label>Type:</label>
				<?php get_type()?>
				<input type="Reset" name="Reset" value="Reset"/></br></br>
				
					<label>Price range:</label>
					<input name="" type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;width:90px;" >
				<br>
				<br>
				<input id="slider" type="text" name="price" class="span2" value=""  data-slider-min="100" data-slider-max="5000" data-slider-step="100" style="width:100%;"/>
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
				  <option selected="selected"></option>
				  <option id="Lowest Price">Lowest Price</option>
				  <option id="Hightest Price">Hightest Price</option>
				  <option id="amount">Lastest Arrival</option>
				  
				</select>
				
			</form>
				
				
				
		</div>
			 
			
			
		
		
	 
		
		<div class="col-md-10" style="padding:5px;">
			<main>
				<?php 
							//print_r($_GET);
							
							
							//var_dump($_GET);
							/*if(empty($_GET['price'])){
								echo"in";
								echo $_GET['price'] . "<script>
									price.setValue([200,400]);
									</script>";
							}*/
							if($_SERVER['REQUEST_METHOD'] == 'GET'){
								$value = $_GET;
								get_result($value,"all");
								
								var_dump(!empty($_GET['sort']));
								if(!empty($_GET['sort'])){
									
									set_selected_sort($_GET['sort']);
								}
								if(empty($_GET['type'])){
									exit;
								}
								
								set_checked_butt($value['type']);
								
								
							}
							/*if(!empty($_GET['price'])){
								$value = $_GET;
								print_r($value);
								get_result($value,'price');
								
							}
							if(!empty($_GET['search'])){
								$value = $_GET['search'];
								$_GET['name'] = $value;
								//var_dump($_GET);
								
								get_result($value,'keyword');
								
							}
							elseif(!empty($_GET['name'])){
								$keyword = $_GET['name'];
								//echo $keyword;
								get_result($keyword,'keyword');
								
							}
							elseif(!empty($_GET['type'])){
								$value = $_GET['type'];
								get_result($value,'type');
								set_checked_butt($value);
							}	
							elseif(!empty($_GET['search'])){
								$value =$_GET['search'];
								get_result($value,'keyword');
							}
							else{
								$value = 0;
							}*/
							function set_checked_butt($value){
								foreach($value as $id){
									echo'<script>						
											$("#'.$id .'").prop("checked", true);

										</script>';
								}
							}
							function set_selected_sort($value){
									print($value);
									echo'<script>						
												$("#'.$value .'").prop("selected", "selected");

											</script>';
							}
							
						
				?>
			</main>  
		</div>
	</div>
		
<?php
	page_footer();


?>