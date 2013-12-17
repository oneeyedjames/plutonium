<?php

header('Content-type: text/plain');

ksort($_SERVER);
ksort($_REQUEST);

echo 'Server: ';
print_r($_SERVER);

echo 'Request: ';
print_r($_REQUEST);

?>