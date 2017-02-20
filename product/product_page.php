
<?php
	include_once('../page_gen.php');
	include_once('product.php');
	page_header("product");

		

	
?>

<script>
	

  $( function() {
	  
	  
	  
		var price = new Slider("#slider", {
		  range: true,
		  value:[250,450],
		  
		});

		
		
		var value = price.getValue();
		$("#amount").val( "$" + value[0] + " - $" + value[1] );
		price.on("slide", function(slideEvt){
			$("#amount").val( "$" + slideEvt[0] + " - $" + slideEvt[1]);
		})
		price.on("slideStop",function(slideEvt)	{
			//var value = price.getValue();
			//$("#amount").val( "$" + value[0] + " - $" + value[1] );
			$("#amount").val( "$" + slideEvt[0] + " - $" + slideEvt[1]);
			//$("#slider").submit();
			$("#adv_search_form").submit();
			var php = "<?php if(!empty($_GET['price'])){echo $_GET['price'];} ?>";
			//document.write(php);
			//document.write(php);
			var new_val = parseInt(php,10);
			new_val_2 = new_val.toString();
			
			document.write(new_val);
			//var new_val = parseInt(new_val,10);
			//document.write(typeof(new_val),new_val);
			document.write(new_val_2);
			//alert(php);
			
			//var min = price.getValue[0];
			//var max = price.getValue[1];
			//price.setValue(new_val);
			
			
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
				<input id="slider" type="text" name="price" class="span2" value=""  data-slider-min="10" data-slider-max="1000" data-slider-step="5" style="width:100%;"/>
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
				
			</form>
				
				
				
		</div>
			 
			
			
		
		
	 
		
		<div class="col-md-10" style="padding:5px;">
			<main>
				<?php 
							//print_r($_GET);
							
							
							var_dump($_GET);
							/*if(empty($_GET['price'])){
								echo"in";
								echo $_GET['price'] . "<script>
									price.setValue([200,400]);
									</script>";
							}*/
							if(!empty($_GET['search'])){
								$value = $_GET['search'];
								get_result($value,'keyword');
								
							}
							elseif(!empty($_GET['name'])){
								$keyword = $_GET['name'];
								//echo $keyword;
								get_result($keyword,'keyword');
								
							}
							elseif(!empty($_GET['type'])){
								$value = $_GET['type'];
								get_result($value,'Tweb_Product_Type');
								set_checked_butt($value);
							}	
							elseif(!empty($_GET['search'])){
								$value =$_GET['search'];
								get_result($value,'keyword');
							}
							else{
								$value = 0;
							}
							function set_checked_butt($value){
								foreach($value as $id){
									echo'<script>						
											$("#'.$id .'").prop("checked", true);

										</script>';
								}
							}
							
						
				?>
			</main>  
		</div>
	</div>
		
<?php
	page_footer();


?>