<?php

	require($_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php');
	use Blocktrail\SDK\BlocktrailSDK;
	$client = new BlocktrailSDK("32b74b23f51fb1726e3389ed3acab9c434965880", "5a5cd3aacdb746487d98064ba5d82b01abd8cd7e", "BTC", true/* testnet */);
	$address = $client->address("2N1MrFG5uzcTe1sxBDmUhPtJRb93HZDWPy1");
	$lateBlock = $client->blockLatest();

	#var_dump($address['balance'], $lateBlock['hash']);

	#var_dump($address);
	#print_r($address);

	print('<h4>last block</h4>');

	foreach ($address as $key => $value) {
		printf('|key : %30s | value:%s <br/>', $key, $value);
	}

	print('<h4>last block</h4><br/>');

	foreach ($lateBlock as $key => $value) {
		printf('|key : %30s | value:%s <br/>', $key, $value);
	}

?>