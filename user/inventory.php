<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/user/inventory_fns.php');

	page_header('inventory');

	if (check_login()) {
                product_refund($_SESSION['login_user_id']);
		product_body($_SESSION['login_user_id']);
                product_refunded_list($_SESSION['login_user_id']);
                //Further development
	} else {
		not_loggedin();
	}

	page_footer();
?>