<?php
	function page_header(){
	

?>

<html>
<head>
	<link href="css/bootstrap.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script>
	</script>
	<style>

	#head_bar{
		margin-bottom:1px;
		background-image:inherit;
		border:none;
	}
	#head_bar a{
     color: #d9edf7;
     text-decoration:none;
	}

	#head_bar a:hover {
		color: lightblue;
		
	}
	#product:hover{
		
	}
	.navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus {
    color: #555;
    background-color: rgba(231, 231, 231, 0.16);
	}
	.dp_item li:hover{
		background-color:rgba(231, 231, 231, 0.16);
	}
	</style>
</head>
<body>
	<div class="wrapper">
		<div style="background-image:url(image/header_bg.jpg);">;
		<header class="header" style="height:12%;">
			<strong>Header:</strong>
			
		</header><!-- .header-->
		
			<nav class="navbar navbar-default" id="head_bar">
				<div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
				   <div class="navbar-header">
					  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>
					  <a class="navbar-brand" href="#">Brand</a>
					</div>

    <!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse"  style="font-size:20px;">
					  <ul class="nav navbar-nav" >
						<div class="col-lg-1" style="width:350px;"></div>
						<li><a href="#">Sale Your Product</a></li>
						<li class="dropdown">
						  <a id="product" onclick="this.color='red';" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Product <span class="caret"></span></a>
						  <ul class="dropdown-menu" style="background-color:#6d8cb1;">
							<li><a href="#" class="dp_item">Action</a></li>
							<li><a href="#" class="dp_item">Another action</a></li>
							<li><a href="#" class="dp_item">Something else here</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#" class="dp_item">Separated link</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#" class="dp_item">One more separated link</a></li>
						  </ul>
						</li>
						<li><a href="#">Account</a></li>
					  </ul>
					  <ul class="nav navbar-nav navbar-right">
						<li><a style="font-size:22px;" ><span class="glyphicon glyphicon-shopping-cart" onmouseover="this.color='red';"></span></a></li>
						<li><a href="#">sign in</a>
						</li>
					  </ul>
					</div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
			</nav>
		</div>


	<div class="container-fluid" style="width:100%; padding:0px; position: absolute;top: 186px;bottom: 0;">
	<!-- content div template -------->

	<!---
		<div class="col-lg-1">
			<strong>Left Sidebar:</strong> 
		</div>    -----left-sidebar--------
		
		<div class="col-lg-11">
			<main>
				<strong>Content:</strong>

			</main> -----content------ 
		</div>
	-->
	
<?php
	}
	
	function page_footer(){

	
?>
</div><!-- .container-->
</div><!-- .wrapper -->
	
	
</body>

</html>

<?php	
	}
?>
	
	
	
	
	
	