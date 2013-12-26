<?php

session_start();

define('PU_PATH_BASE', dirname(dirname(__FILE__)));
define('PU_PATH_LIB', realpath(PU_PATH_BASE . '/libraries'));
define('PU_PATH_FUNC', realpath(PU_PATH_LIB . '/Plutonium/Functions'));

spl_autoload_register(function ($class) {
	$file = str_replace('_', '/', $class) . '.php';
	$path = realpath(PU_PATH_LIB . '/' . $file);
	if (is_file($path)) require_once($path);
});

require_once PU_PATH_BASE . '/constants.php';
require_once PU_PATH_FUNC . '/Strings.php';
require_once PU_PATH_FUNC . '/Arrays.php';

?>