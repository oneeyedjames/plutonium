<?php

define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('LS', PHP_EOL);
define('BS', '\\');
define('FS', '/');

$root = str_replace(BS, FS, realpath($_SERVER['DOCUMENT_ROOT']));
$path = str_replace(BS, FS, realpath(dirname($_SERVER['SCRIPT_FILENAME'])));

defined('PU_PATH_ROOT') or define('PU_PATH_ROOT', $root);
defined('PU_PATH_BASE') or define('PU_PATH_BASE', $path);

unset($root, $path);

$scheme = empty($_SERVER['HTTPS']) ? 'http' : 'https';

$host = $_SERVER['SERVER_NAME'];
$path = str_replace(PU_PATH_ROOT, '', PU_PATH_BASE);

defined('PU_URL_ROOT') or define('PU_URL_ROOT', $scheme . '://' . $host);
defined('PU_URL_PATH') or define('PU_URL_PATH', $path);
defined('PU_URL_BASE') or define('PU_URL_BASE', PU_URL_ROOT . PU_URL_PATH);

unset($scheme, $host, $path);

?>
