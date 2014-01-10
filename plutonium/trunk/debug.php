<?php

header('Content-type: text/plain');
$const = get_defined_constants(true);

ksort($_SERVER);
ksort($_REQUEST);

if (isset($const['user']))
	ksort($const['user']);

echo 'Server: ';
print_r($_SERVER);

echo 'Request: ';
print_r($_REQUEST);

echo 'Constants: ';
print_r($const['user']);

?>