<?php
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/product_manage/upload_fns.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/page_gen.php');
	include_once($_SERVER['DOCUMENT_ROOT'] . '/EIE3117_trading_web/session/checking.php');
        require_once($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/session/redirect_page.php');

	page_header('Upload');

	if (check_login()) {
		upload_form();
	} else {
		response_message2rediect("Please login", "./user/login.php");
	}
	page_footer();

?>