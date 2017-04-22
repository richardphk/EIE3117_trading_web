<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/user/record_fns.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');

	page_header('Purchase Record');

        //Check whether the user has logged in.
	if (check_login()) {
            //Call the functions for displaying all the purchase history.
            table_header();
            table($_SESSION['login_user_id']);
            table_footer();
	} else {
            not_loggedin();
	}

	page_footer();


?>