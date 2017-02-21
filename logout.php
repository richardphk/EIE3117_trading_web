<?php
	session_start();
   
	if(session_destroy()) {
		
		$home = sprintf('Refresh:0; url=%s/EIE3117_trading_web/home.php', 'http://158.132.145.246');
		//print($home);
		header($home);
		exit;
	}
?>