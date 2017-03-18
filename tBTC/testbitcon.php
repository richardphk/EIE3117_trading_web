<?php

	require($_SERVER['DOCUMENT_ROOT'].'/EIE3117_trading_web/vendor/autoload.php');
	use Blocktrail\SDK\BlocktrailSDK;
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