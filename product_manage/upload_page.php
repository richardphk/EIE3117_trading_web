<?php
	include_once('upload_fns.php');
	include_once('../page_gen.php');
	include_once('../session/checking.php');
	
	page_header('Upload');
	
	if (check_login()) {
		upload_form();
	} else {
		not_loggedin();
	}
	page_footer();

?>