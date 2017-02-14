<?PHP
	function response_message2rediect($msg, $page){
		echo '<script type="text/javascript">';
		echo 'alert("'.$msg.'");';
		echo 'window.location.href = "'.$page.'"';
		echo '</script>';
	}
?>