<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/user/record_fns.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/session/checking.php');
	

	page_header('Purchase Record');

	if (check_login()) {
		table_header();
		table($_SESSION['login_user_id']);
		table_footer();
	} else {
		not_loggedin();
	}

	page_footer();


?>