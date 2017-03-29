<?php
	session_start();

	$product_id = "P00002";
	add_order($product_id);

	function add_order($product_id){
		$_SESSION['product_id'] = $product_id;
	}
?>