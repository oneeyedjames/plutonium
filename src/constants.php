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

defined('PU_URL_SCHEME') or define('PU_URL_SCHEME', $scheme);

list($host, $port) = array_pad(explode(':', $_SERVER['HTTP_HOST'], 2), 2, '80');

defined('PU_URL_HOST') or define('PU_URL_HOST', $host);
defined('PU_URL_PORT') or define('PU_URL_PORT', (int)$port);

$root = "$scheme://$host";
$path = str_replace(PU_PATH_ROOT, '', PU_PATH_BASE);

if ($port != 80) $root .= ":$port";

defined('PU_URL_ROOT') or define('PU_URL_ROOT', $root);
defined('PU_URL_PATH') or define('PU_URL_PATH', $path);
defined('PU_URL_BASE') or define('PU_URL_BASE', $root . $path);

unset($scheme, $host, $port, $root, $path);
