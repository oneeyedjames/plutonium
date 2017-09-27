<?php

$_SERVER['SERVER_NAME'] = 'plutonium.dev';

$_SERVER['DOCUMENT_ROOT'] = dirname(dirname(__FILE__));

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI']    = '/';

session_start();

spl_autoload_register(function ($class) {
	$file = str_replace(BS, DS, $class) . '.php';
	$path = realpath(PU_PATH_LIB . '/' . $file);
	if (is_file($path)) require_once $path;
});

define('PU_PATH_BASE', realpath($_SERVER['DOCUMENT_ROOT']));
define('PU_PATH_LIB', realpath(PU_PATH_BASE . '/libraries'));
define('PU_PATH_FUNC', realpath(PU_PATH_LIB . '/Plutonium/Functions'));

require_once PU_PATH_BASE . '/constants.php';
require_once PU_PATH_FUNC . '/Strings.php';
require_once PU_PATH_FUNC . '/Arrays.php';
