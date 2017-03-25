<?php
	require_once('BlockTrail_API.php');
	function create_wallet($wallet_ac, $wallet_pw){
		//return wallet
		return list($wallet, $primaryMnemonic, $backupMnemonic, $blocktrailPublicKeys) = $client->createNewWallet($wallet_ac, $wallet_pw);
	}

	function init_wallet($wallet_ac, $wallet_pw){
		//return wallet object
		$wallet = $client->initWallet($wallet_ac, $wallet_pw);
		return $wallet;
	}

	function receive_tran($wallet){
		//return address
		$address = $wallet->getNewAddress();
		return $address;
	}

	function wallet_balance($wallet){
		//return Confirmed Balance and Unconfirmed Balance
		list($confirmedBalance, $unconfirmedBalance) = $wallet->getBalance();
		$balance = list(BlocktrailSDK::toBTC($confirmedBalance), BlocktrailSDK::toBTC($unconfirmedBalance));
		return $balance;
	}

	function send_tran($tran_wallet, $receive_address, $value){
		$value = BlocktrailSDK::toSatoshi(1.1);
		$wallet->pay(array($receive_address => $value), null, false, true, Wallet::FEE_STRATEGY_BASE_FEE);
		$wallet->pay(array($receive_address => $value), null, false, true, Wallet::FEE_STRATEGY_LOW_PRIORITY);
	}

	function list_tran_history($wallet){
		$transactions = $wallet->transactions();
		return $transactions;
	}

?>