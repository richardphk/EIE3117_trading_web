<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/user/inventory_fns.php');

	page_header('inventory');
	
	if (check_login()) {
		product_header();
		product_body($_SESSION['login_user_id']);
		product_footer();
	} else {
		not_loggedin();
	}
	
	page_footer();
?>