<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/product_manage/upload_fns.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/session/checking.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/session/redirect_page.php');

	page_header('Upload');

	if (check_login()) {
		upload_form();
	} else {
		response_message2rediect("Please login", "./user/login.php");
	}
	page_footer();

?>