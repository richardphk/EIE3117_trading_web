<?php
	include('BlockTrail_API.php');
	use Blocktrail\SDK\BlocktrailSDK;
	use Blocktrail\SDK\Connection\Exceptions\InvalidCredentials;

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