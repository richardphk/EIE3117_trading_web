<?php
	include_once('../page_gen.php');
	include_once('record_fns.php');

	$user_id = 'U00001';

	page_header('Purchase Record');
	table_header();
	table($user_id);
	table_footer();


?>