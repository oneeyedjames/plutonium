<pre><?php
	$const = get_defined_constants(true);

	ksort($_SERVER);

	echo 'Server: ';
	print_r($_SERVER);

	echo 'Constants: ';
	print_r($const['user']);

	echo 'Request: ';
	print_r($this->_application->request->toArray());
?></pre>