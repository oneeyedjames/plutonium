<pre><?php
	$const = get_defined_constants(true);

	$registry =& Plutonium_Registry::getInstance();
	$request  =& Plutonium_Request::getInstance();

	ksort($_SERVER);

	echo 'Constants: ';
	print_r($const['user']);

	echo 'Configuration: ';
	print_r($registry->config->toArray());

	echo 'Request: ';
	print_r($request->toArray());

	echo 'Server: ';
	print_r($_SERVER);
?></pre>