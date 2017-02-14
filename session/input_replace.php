<?PHP
	function input_replace($str){
		# whatever small special code replacing
		$str = str_replace('--', '', $str);
		$str = str_replace(' ', '', $str);
		$str = str_replace('&nbsp;', '', $str);
		return $str;
	}
?>