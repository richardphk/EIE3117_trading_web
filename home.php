<?php
	include_once('page_gen.php');
	page_header();
?>
		<style>
		#main{
			 position: relative;
			 top: 15%;
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
			background-color:rgba(255, 255, 255, 0.26);
			width:500px;
			height:100px;
			font-size:50px;
			color:#555;
		}
		</style>
		<div class="col-lg" id="content">
			<main id="main">
				<center><strong>Main</strong>
				
				<form class="navbar-form" style="margin: 0px; height:50px;">
					<div class="input-group" style="width:100px;">
					  <input id="search" type="text" class="form-control" placeholder="Search for..." style="">
					  <span class="input-group-btn">
					  <button type="submit" class="btn btn-default">Submit</button>
					  </span>
					</div>
					
				</form></center>

				
			</main>
		</div>
		
		
		
<?
	page_footer();
?>