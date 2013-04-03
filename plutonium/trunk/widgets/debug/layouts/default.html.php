<pre><?php
	$const = get_defined_constants(true);

	$registry =& Plutonium_Registry::getInstance();
	$request  =& Plutonium_Request::getInstance();

	echo 'Contstants: ';
	print_r($const['user']);
	
	echo 'Configuration: ';
	print_r($registry->config->toArray());
	
	echo 'Request: ';
	print_r($request->toArray());
?></pre>