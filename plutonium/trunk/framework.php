<?php
/**
 * @version		0.9.0
 * @package		Plutonium
 * @author		J Andrew Scott
 * @copyright	Copyright (C) 2010 J Andrew Scott. All rights reserved.
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 */

define('PLUTONIUM_VERSION', '0.9.0');

define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('LS', PHP_EOL);
define('BS', '\\');
define('FS', '/');

defined('P_ROOT_PATH') or define('P_ROOT_PATH', realpath($_SERVER['DOCUMENT_ROOT']));
defined('P_BASE_PATH') or define('P_BASE_PATH', realpath(dirname($_SERVER['SCRIPT_FILENAME'])));

$scheme = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$host   = $_SERVER['SERVER_NAME'];
$path   = str_replace(P_ROOT_PATH, '', P_BASE_PATH);
$path   = str_replace(BS, FS, $path);
$path   = trim($path, FS);

defined('P_ROOT_URL') or define('P_ROOT_URL', $scheme . '://' . $host);
defined('P_BASE_URL') or define('P_BASE_URL', P_ROOT_URL . FS . $path);
defined('P_PATH_URL') or define('P_PATH_URL', $path);

unset($scheme, $host, $path);

require_once P_BASE_PATH . '/libraries/Plutonium/Loader.php';

Plutonium_Loader::autoload(P_BASE_PATH . '/libraries');

?>