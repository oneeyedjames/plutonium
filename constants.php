<?php

define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('LS', PHP_EOL);
define('BS', '\\');
define('FS', '/');

$root = str_replace(BS, FS, realpath($_SERVER['DOCUMENT_ROOT']));
$path = str_replace(BS, FS, realpath(dirname(__FILE__)));

defined('PU_PATH_ROOT') or define('PU_PATH_ROOT', $root);
defined('PU_PATH_BASE') or define('PU_PATH_BASE', $path);

unset($root, $path);

$scheme = empty($_SERVER['HTTPS']) ? 'http' : 'https';

$host = $_SERVER['HTTP_HOST'];
$path = str_replace(PU_PATH_ROOT, '', PU_PATH_BASE);

list($host, $port) = array_pad(explode(':', $host, 2), 2, '80');

$port = $port != 80 ? ':' . $port : '';

defined('PU_URL_ROOT') or define('PU_URL_ROOT', $scheme . '://' . $host . $port);
defined('PU_URL_PATH') or define('PU_URL_PATH', $path);
defined('PU_URL_BASE') or define('PU_URL_BASE', PU_URL_ROOT . PU_URL_PATH);

unset($scheme, $host, $port, $path);
