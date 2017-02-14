
<?php
	include_once('../page_gen.php');
	include_once('product.php');
	page_header("product");
	
?>
	<div class="row">
		<div class="col-md-2" style="background-color:white; height:100%;">
			<strong>Left Sidebar:</strong> 
		</div>   
		
		<div class="col-md-10" style="padding:5px;">
			<main>
				<?php get_result();?>
			</main>  
		</div>
	</div>
		
<?php
	page_footer();


?>