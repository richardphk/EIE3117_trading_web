<?php
	include_once('record_fns.php');
	include_once('../page_gen.php');
	include_once('../session/checking.php');
	
	session_start();

	page_header('Purchase Record');
	if (check_login()) {
		table_header();
		table($_SESSION['login_user']);
		table_footer();
	} else {
		not_loggedin();
	}
	page_footer();


?>