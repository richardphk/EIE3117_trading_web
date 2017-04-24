<?PHP
	/**
	 * rediect page and alert message in js
	 * @param  [String] $msg  [message]
	 * @param  [String] $page [URL]
	 */
	function response_message2rediect($msg, $page){
		echo '<script type="text/javascript">';
		echo 'alert("'.$msg.'");';
		echo 'window.location.href = "'.$page.'"';
		echo '</script>';
		exit();
	}
?>