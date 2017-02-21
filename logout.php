<?php
	session_start();
   
	if(session_destroy()) {
		
		$home = sprintf('Refresh:0; url=%s/EIE3117_trading_web/home.php', 'http://localhost');
		//print($home);
		header($home);
		exit;
	}
?>