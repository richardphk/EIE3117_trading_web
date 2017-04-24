<?php
	/**
 	* the interface php in loading API
 	*/
	require('vendor/autoload.php');
	use Blocktrail\SDK\BlocktrailSDK;
	use Blocktrail\SDK\Connection\Exceptions\InvalidCredentials;

	$client = new BlocktrailSDK("d1ab7a1d30951adee018ee341e1969f1af020372", "c9a9d1a2ed36a4be41b08c2f8497c3c50fdb9381", "BTC", true/* testnet */);

	#var_dump($client);
	/*
	try {
	    $address = $client->address("2NFNXx3FfFk57LqvqDvLMWUrHbF3EG6G77M");
	    foreach ($address as $key => $value) {
	    	print $key.':'.$value.'<br/>';
	    }
	} catch (Exception $e) {
	    if ($e instanceof InvalidCredentials) {
	        var_dump((string)$e);
	    }
	}
	*/

?>