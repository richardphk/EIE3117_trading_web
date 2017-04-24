<?php
	/**
	 * database connection function
	 * PDO function
	 * @param  [String] $username [databse username]
	 * @param  [String] $password [databse user password]
	 * @return [Object] PDO databse Object
	 */
	function db_connect($username, $password){
		$DB_server = 'localhost';
		$DB_username = $username;
		$DB_password = $password;
		$DB_databasename = 'trading_web';

		try{
			$db = new PDO("mysql:host=".$DB_server.";"."dbname=".$DB_databasename.";charset=utf8", $DB_username, $DB_password);
			$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			#echo "<script type='text/javascript'>alert('OK! DataBase Connected!');</script>";
			return $db;
		}catch(PDOException $e) {
			$m = $e->getMessage();
			//echo $m;
			if(strpos($m, "Access denied for user") == true){
				echo '<script type="text/javascript">';
				echo 'alert("Log-in error:Wrong User or Password of DataBase!");';
				echo 'window.location.href = "../user/login.php"';
				echo '</script>';
				die();
			}
		}
	}
?>