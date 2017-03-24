<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/config_db/config_db.php');

	$id = $_POST['id'];
	$cid = $_POST{'cid'};
	$value = $_POST['Credit'];
	function addValue($value){
		$db_conn = db_connect('root','root');
		$result = $db_conn->prepare("INSERT INTO `trading_web`.`Tweb_User_Credit`(`Tweb_User_Credit_id`, `Tweb_User_ID`, `Tweb_User_Credit_Cash`) 
										VALUES (:cid, :id, :val);");
		$result->bindValue(':val', $value);
		$result->execute();
		$rec = $result->fetchAll(PDO::FETCH_ASSOC);
	}






















?>