<?php
	function page_header(){
		echo'<nav class="navbar navbar-default navbar-static" id="navbar-example"> <div class="container-fluid"> <div class="navbar-header"> <button class="collapsed navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-example-js-navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a href="#" class="navbar-brand">Project Name</a> </div> <div class="collapse navbar-collapse bs-example-js-navbar-collapse"> <ul class="nav navbar-nav"> <li class="dropdown"> <a href="#" class="dropdown-toggle" id="drop1" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Dropdown <span class="caret"></span> </a> <ul class="dropdown-menu" aria-labelledby="drop1"> <li><a href="#">Action</a></li> <li><a href="#">Another action</a></li> <li><a href="#">Something else here</a></li> <li role="separator" class="divider"></li> <li><a href="#">Separated link</a></li> </ul> </li> <li class="dropdown"> <a href="#" class="dropdown-toggle" id="drop2" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Dropdown <span class="caret"></span> </a> <ul class="dropdown-menu" aria-labelledby="drop2"> <li><a href="#">Action</a></li> <li><a href="#">Another action</a></li> <li><a href="#">Something else here</a></li> <li role="separator" class="divider"></li> <li><a href="#">Separated link</a></li> </ul> </li> </ul> <ul class="nav navbar-nav navbar-right"> <li id="fat-menu" class="dropdown"> <a href="#" class="dropdown-toggle" id="drop3" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> Dropdown <span class="caret"></span> </a> <ul class="dropdown-menu" aria-labelledby="drop3"> <li><a href="#">Action</a></li> <li><a href="#">Another action</a></li> <li><a href="#">Something else here</a></li> <li role="separator" class="divider"></li> <li><a href="#">Separated link</a></li> </ul> </li> </ul> </div> </div> </nav>';
	}


?>

<html>
<head>
	<link href="css/bootstrap.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/bootstrap.min.js"></script>
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
						  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Product <span class="caret"></span></a>
						  <ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#">Separated link</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="#">One more separated link</a></li>
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


	<div class="container-fluid" style="height:78%;">

		<div class="col-lg-1">
			<strong>Left Sidebar:</strong> 
		</div><!-- .left-sidebar -->
		
		<div class="col-lg-11">
			<main class="content">
				<strong>Content:</strong>

			</main><!-- .content -->
		</div>
	</div>

















</div><!-- .wrapper -->
	
	
</body>

</html>