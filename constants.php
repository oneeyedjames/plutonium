<?php

// Basic Contants
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('LS', PHP_EOL);
define('BS', '\\');
define('FS', '/');

// Platform Version
define('PU_VERSION', '0.9.0');

// Local Environment
defined('PU_PATH_ROOT') or define('PU_PATH_ROOT',
	realpath($_SERVER['DOCUMENT_ROOT']));
defined('PU_PATH_BASE') or define('PU_PATH_BASE',
	realpath(dirname($_SERVER['SCRIPT_FILENAME'])));

$scheme = empty($_SERVER['HTTPS']) ? 'http' : 'https';

$host = $_SERVER['SERVER_NAME'];
$path = str_replace(array(BS, PU_PATH_ROOT), array(FS, ''), PU_PATH_BASE);

defined('PU_URL_ROOT') or define('PU_URL_ROOT', $scheme . '://' . $host);
defined('PU_URL_PATH') or define('PU_URL_PATH', $path);
defined('PU_URL_BASE') or define('PU_URL_BASE', PU_URL_ROOT . PU_URL_PATH);

unset($scheme, $host, $path);

?>