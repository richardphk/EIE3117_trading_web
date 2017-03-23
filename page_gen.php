<?php
	function page_header($title){
		require_once($_SERVER['DOCUMENT_ROOT'].'/session/create_session.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/session/checking.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/user/salt.php');
		start_session();


		if(isset($_COOKIE['user']) && $_COOKIE['user'] != ''){
			list($hash, $login_user, $login_user_id) = explode('-', $_COOKIE['user']);
			if(hash('sha256', $salt.$login_user.$login_user_id.$salt.$login_user_id.$salt) == $hash){
				$_SESSION['login_user'] = $login_user;
				$_SESSION['login_user_id'] = $login_user_id;
				$_SESSION['login_user_privilege'] = '';
			}
			//print_r($_COOKIE);
		}

?>

<html>
<head>
	<title><?php echo $title; ?></title>

<?php
	//get folder link
	$host_uri = $_SERVER['HTTP_HOST'];
	$self = explode('/', dirname($_SERVER['PHP_SELF']));
	$folder_eie = $self[0];
	$folder_link = sprintf('http://%s/', $host_uri, $folder_eie);
	//print($folder_link);
	printf('<base href="%s" target="_self">', $folder_link);
?>
	<link href="css/bootstrap.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-slider.min.js"></script>
	<link href="css/bootstrap-slider.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Lora:700i" rel="stylesheet">
	<!--<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		-->
	<script>
	 //function check_input_search(){
	//	 var ele = document.getElementsByClass("check");
	//	 ele
	 //}
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
	.navbar-nav > li > a {
    padding-top: 10px;
    padding-bottom: 10px;
    line-height: normal;
}
	
	</style>
</head>
<body style="background-color:#eee;">
	<div class="wrapper" style="background-color:#eee;">
		<div style="background-image:url(image/header_bg.jpg);">
		<header class="header" style="height:12%;">
			<center style="font-family: 'Lora', serif;color:#333; font-size:50px;color:rgb(193, 221, 234);">Online Trading Website</strong>

		</header><!-- .header-->

			<nav class="navbar navbar-default" id="head_bar">
				<div class="container-fluid"><!-- Brand and toggle get grouped for better mobile display -->
				   <div class="navbar-header">
					  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>

					<a class="navbar-brand" href="home.php">Home</a>
					</div>

    				<!-- Collect the nav links, forms, and other content for toggling -->
					<span style="font-family: 'Lora', serif;">
					<div class="collapse navbar-collapse"  style="font-size:20px;">
					  <ul class="nav navbar-nav" >
						<div class="col-lg-1" style="width:350px;"></div>
						<li><a href="product_manage/upload_page.php">Sale Your Product</a></li>
						<li class="dropdown">
						  <a id="product" onclick="this.color='red';" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Product <span class="caret"></span></a>
						  <ul class="dropdown-menu" style="background-color:#6d8cb1;">
							<li><a href="product/product_page.php?type%5B%5D=Printer&price=100%2C5000&name=&sort=" class="dp_item">Printer</a></li>
							<li><a href="product/product_page.php?type%5B%5D=Router&price=100%2C5000&name=&sort=" class="dp_item">Router</a></li>
							<li><a href="product/product_page.php?type%5B%5D=Notebook&price=100%2C5000&name=&sort=" class="dp_item">Notebook</a></li>
							<li role="separator" class="divider"></li>
							<li><a href="product/product_page.php" class="dp_item">All Products</a></li>
							<li role="separator" class="divider"></li>

						  </ul>
						</li>

					  </ul>
					  <ul class="nav navbar-nav navbar-right">
						<li><a href="order/cart_page.php" style="font-size:22px;" ><span class="glyphicon glyphicon-shopping-cart"></span></a></li>
						<?php
							if(!isset($_SESSION['login_user'])) {
								echo '<li class="dropdown">';
									echo '<a id="product" onclick="this.color=\'red\' href="./user/login.php"
											class="dropdown-toggle" data-toggle="dropdown"
											role="button" aria-haspopup="true" aria-expanded="false">';
									echo 'Account';
									echo '<span class="caret"></span></a>';
									echo '<ul class="dropdown-menu" style="background-color:#6d8cb1;">';
										echo '<li><a href="user/login.php" class="1">Login</a></li>';
										echo '<li><a href="user/forget_pw_page.php" class="1">Forget Password</a></li>';
										echo '<li><a href="user/register.php" class="2">Register</a></li>';
									echo '</ul>';
								echo '</li>';

								//echo '<li><a href="./user/login.php">sign in</a>';
							} else {
								//echo '<li><a href="#">'.$_SESSION['login_user'].'</a>';
								//echo $_SESSION['login_user'] . ' Remain Credit: ';// . remain_credit($_SESSION['login_user_id']);
								echo '<li style="font-size:10px;"><a href="#">Credit:
																			<br>Bitcon:</br</a></li>'; 
								//echo '<li><a href="#">Bitcon:</a></li>'; 

								echo '<li class="dropdown">';
									echo '<a id="product" onclick="this.color=\'red\' href="#"
											class="dropdown-toggle" data-toggle="dropdown"
											role="button" aria-haspopup="true" aria-expanded="false">';
									echo $_SESSION['login_user'];
									echo '<span class="caret"></span></a>';
									echo '<ul class="dropdown-menu" style="background-color:#6d8cb1;">';
										echo '<li><a href="user/inventory.php" class="1">My products</a></li>';
										echo '<li><a href="user/record.php" class="2">Purchase History</a></li>';
										echo '<li><a href="user/reset_pw_page.php" class="3">Reset Password</a></li>';
										echo '<li><a href="logout.php" class="4">Logout</a></li>';
										echo '<li><a href="user/user_info_page.php" class="4">User Information</a></li>';
									echo '</ul>';
								echo '</li>';
							}
						?>
						</li>
					  </ul>
					</div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
			</nav>
		</div></span>

	<div class="container-fluid" style="width:100%; padding:0px; position: relative;height:80.3%;bottom: 0;">


<?php
	}
		function remain_credit($id) {
            include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');
            $db_conn = db_connect('root','');
            $result = $db_conn->prepare('SELECT Tweb_User_Credit_Cash FROM Tweb_User_Credit WHERE Tweb_User_ID = "' . $id . '"');

            $result->execute();
            $rec = $result->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($rec as $r) {
                return $r['Tweb_User_Credit_Cash'];
            }
        }
	function not_loggedin() {
		?>

			<div class="jumbotron">
				<h1>You have not logged in.</h1>
			</div>

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
