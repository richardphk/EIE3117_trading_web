<?php
	include_once('page_gen.php');
	
	page_header('Home');
?>
		<style>
		#main{
			 position: relative;
			 top: 20%;
			 color:lightblue;
			 font-size:50px;
		}
		#content{
			height:100%;
			background-image:url(image/home.jpg);
			background-position: 50%;
			background-size: cover;
		}
		#search{
			width:500px;
			height:45px;
			font-size:25px;
			color:#555;
		}
		</style>
		<div class="col-lg" id="content">
			<main id="main">
				<center style="font-family: 'Lora', serif;color:#333;">Search Your Product
				<form class="navbar-form check" name="home_search_form" action="./product/product_page.php" style="margin: 0px; height:50px;">
					<div class="input-group" style="width:100px;">
					  <input id="search" type="text" class="form-control" name="search" placeholder="Search for..." required>
					  <span class="input-group-btn">
						  <button type="submit" class="btn btn-default"  style="height:45px;" action="this.form.submit()" >
							<span class="glyphicon glyphicon-search"></span>
						  </button>
					  </span>
					</div>
					
				</form></center>

				
			</main>
		</div>

<?
	page_footer();
?>