<?php
   session_start();
   
   if(session_destroy()) {
      $home = sprintf('Refresh:0; url=%s/home.php', 'http://'.$_SERVER['HTTP_HOST']);
		//print($home);
		header($home);
	  	exit;
   }
?>