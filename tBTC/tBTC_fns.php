<?php
	/**
	 * all functions of the API
	 */
	include('BlockTrail_API.php');
	use Blocktrail\SDK\BlocktrailSDK;
	use Blocktrail\SDK\Connection\Exceptions\InvalidCredentials;


	/**
	 * create wallet
	 * @param  [String] $wallet_ac [Usernaem]
	 * @param  [String] $wallet_pw [Password]
	 * @param  [Object] $client    [API Object]
	 * @return [String] ok message or exception
	 */
	function create_wallet($wallet_ac, $wallet_pw, $client){
		//return wallet
		try {
    		list($wallet, $primaryMnemonic, $backupMnemonic, $blocktrailPublicKeys) = $client->createNewWallet($wallet_ac, $wallet_pw);
    		return 'ok';
		} catch (Exception $e) {
    		if ($e instanceof InvalidCredentials) {
    			print '';
        		#var_dump((string)$e);
    		} else {
    			print '';
    			//$e
    		}
		}
	}

	/**
	 * init wallet
	 * @param  [String] $wallet_ac [Usernaem]
	 * @param  [String] $wallet_pw [Password]
	 * @param  [Object] $client    [API Object]
	 * @return [Object] wallet object
	 */
	function init_wallet($wallet_ac, $wallet_pw, $client){
		//return wallet object
		try {
    		$wallet = $client->initWallet($wallet_ac, $wallet_pw);
			return $wallet;
		} catch (Exception $e) {
    		if ($e instanceof InvalidCredentials) {
        		var_dump((string)$e);
    		} else {
    			print $e;
    		}
		}
	}

	/**
	 * receive bitcon address
	 * @param  [Object] $client    [API Object]
	 * @return [String] wallet receive address
	 */
	function receive_tran($wallet){
		//return receive address
		try {
    		$address = $wallet->getNewAddress();
			return $address;
		} catch (Exception $e) {
    		if ($e instanceof InvalidCredentials) {
        		var_dump((string)$e);
    		} else {
    			print $e;
    		}
		}
	}

	/**
	 * wallet balance
	 * @param  [Object] wallet object
	 * @return Confirmed Balance, Unconfirmed Balance in bitcon
	 */
	function wallet_balance($wallet){
		//return Confirmed Balance and Unconfirmed Balance
		try {
    		list($confirmedBalance, $unconfirmedBalance) = $wallet->getBalance();
    		$confirmedBalance = sprintf("%2.8f",$confirmedBalance/100000000);
			$unconfirmedBalance = sprintf("%2.8f",$unconfirmedBalance/100000000);
			return [$confirmedBalance, $unconfirmedBalance];
		} catch (Exception $e) {
    		if ($e instanceof InvalidCredentials) {
        		var_dump((string)$e);
    		} else {
    			print $e;
    		}
		}
	}

	/**
	 * send bitcon
	 * @param  [Object] wallet object
	 * @param  [String] receive address
	 * @param  [Double] $value -> bitcon value
	 * @return output API exception if the API caused problems
	 */
	function send_tran($wallet, $receive_address, $value){
		try {
    		$value = BlocktrailSDK::toSatoshi($value);
    		#print_r($value);
			$wallet->pay(array($receive_address => $value), null, false, true);
		} catch (Exception $e) {
    		if ($e instanceof InvalidCredentials) {
        		var_dump((string)$e);
    		} else {
    			print $e;
    		}
		}

	}
	/**
	 * list transaction history
	 * @param  [type] $wallet [description]
	 * @return [type]         [description]
	 */
	function list_tran_history($wallet){
		try {
    		$transactions = $wallet->transactions();
			return $transactions;
		} catch (Exception $e) {
    		if ($e instanceof InvalidCredentials) {
        		var_dump((string)$e);
    		} else {
    			print $e;
    		}
		}

	}

	#$w = create_wallet('ac111', 'pw111', $client);
	#var_dump($w);
	/*
	$wallet = init_wallet('ac111', 'pw111', $client);
	#var_dump($wallet);

	$balance = wallet_balance($wallet);
	echo "balance:";
	print_r($balance);
	echo "<br/>";

	$rece = receive_tran($wallet);
	echo "new receive address:";
	print $rece;
	echo "<br/>";

	$his = list_tran_history($wallet);
	echo "history:";
	print_r($his);
	echo "<br/>";
	*/
?>