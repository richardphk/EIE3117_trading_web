<?php
	include_once('record_fns.php');
	include_once('../page_gen.php');
	include_once('../session/checking.php');
	

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