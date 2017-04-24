<?PHP
	/**
	 * the function for generate tokem
	 * @param  [String] $salt     [salt of hashing]
	 * @param  [String] $username [Username]
	 * @param  [String] $password [Password]
	 * @param  [Date -> String] $today    [Today date]
	 * @param  [Time] $now_time [time() in php]
	 * @return [Array]  token & token expire time
	 */
	function gen_token($salt, $username, $password, $today, $now_time){
		$random_words = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(13/strlen($x)) )), 1, 13);
		$token = hash('sha256', $salt.$username.$password.$today.$random_words.$now_time);
		$token_exptime = time() + 60*60*2; //set token can active for 2 hours only
		return [$token, $token_exptime];
	}

?>