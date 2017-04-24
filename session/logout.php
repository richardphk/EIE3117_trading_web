<?php
   session_start();
   if(session_destroy()) {
   		if(isset($_SERVER['HTTPS'])){
      		$home = sprintf('Refresh:0; url=%s/home.php', 'https://'.$_SERVER['HTTP_HOST']);
      	} else {
      		$home = sprintf('Refresh:0; url=%s/home.php', 'http://'.$_SERVER['HTTP_HOST']);
      	}
		//print($home);
		header($home);
	  	exit;
   }
?>