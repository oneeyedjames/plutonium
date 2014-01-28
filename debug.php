<?php

header('Content-type: text/plain');
$const = get_defined_constants(true);

ksort($_SERVER);
ksort($_REQUEST);

echo 'Server: '  . print_r($_SERVER,  true);
echo 'Request: ' . print_r($_REQUEST, true);

if (isset($const['user'])) {
	ksort($const['user']);

	echo 'Constants: ' . print_r($const['user'], true);
}

?>