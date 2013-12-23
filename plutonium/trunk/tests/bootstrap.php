<?php

session_start();

define('PU_PATH_BASE', dirname(dirname(__FILE__)));
define('PU_PATH_LIB', realpath(PU_PATH_BASE . '/libraries/Plutonium'));

spl_autoload_register(function ($class) {
	$file = str_replace('_', '/', $class) . '.php';
	$path = realpath(PU_PATH_BASE . '/libraries/' . $file);

	if (is_file($path)) require_once($path);
});

require_once 'constants.php';

require_once PU_PATH_LIB . '/Functions/String.php';
require_once PU_PATH_LIB . '/Functions/Array.php';

?>