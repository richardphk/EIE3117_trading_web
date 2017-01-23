<?php
   $DB_server = 'localhost';
   $DB_username = 'root';
   $DB_password = 'root';
   $DB_databasename = 'elearning';
   
   try{
		$db = new PDO("mysql:host=".$DB_server.";"."dbname=".$DB_databasename.";charset=utf8", $DB_username, $DB_password);
		$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		#echo "<script type='text/javascript'>alert('OK! DataBase Connected!');</script>";			
	}catch(PDOException $e) {
		$m = $e->getMessage();
		echo $m;
		if(strpos($m, "Access denied for user") == true){
			echo '<script type="text/javascript">'; 
			echo 'alert("Log-in error:Wrong User or Password of DataBase!");'; 
			echo 'window.location.href = "login.html"';
			echo '</script>';
			die();
		}
	}
?>