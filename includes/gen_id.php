<?PHP
	function gen_id($args){
		//args = table name
		//args : Tweb_User_ID->U, Tweb_Product_ID->P, Tweb_Sale_Record_ID->S, Tweb_Order_ID->O, Tweb_Payment_ID->A
		//return false means error
		require_once('../config_db/Config_db.php');
		
		// get db
		$db = db_connect('root', '');
		
		// matching
		switch ($args){
			case "Tweb_User":
				$id = "Tweb_User_ID";
				$tag = "U";
				break;
			case "Tweb_Product":
				$id = "Tweb_Product_ID";
				$tag = "P";
				break;
			case "Tweb_Sale_Record":
				$id = "Tweb_Sale_Record_ID";
				$tag = "S";
				break;
			case "Tweb_Order":
				$id = "Tweb_Order_ID";
				$tag = "O";
				break;
			case "Tweb_Payment":
				$id = "Tweb_Payment_ID";
				$tag = "A";
				break;
			default:
				return false;
		}
		
		//echo $id;
		//echo $args;
		
		$sql_id = "select ".$id." from ".$args." Order by ".$id." DESC Limit 1;";
		$result = $db->prepare($sql_id);
		$result->execute();
		$row = $result->fetch(PDO::FETCH_NUM);
		
		if(empty($row[0])){
			return $tag."00001";
		} else {
			preg_match_all('!\d+!', $row[0], $result);
			$result = $result[0][0] + 1;
			$result = str_pad($result, 5, '0', STR_PAD_LEFT);
			//print('ok'.$tag.$result);
			return ($tag.$result);
		}
	}
	//gen_id('Tweb_User');
?>