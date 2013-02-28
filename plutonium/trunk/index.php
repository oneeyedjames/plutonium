<?php
/**
 * @version   0.9.0
 * @package   Plutonium
 * @author    J Andrew Scott
 * @copyright Copyright (C) 2010 J Andrew Scott. All rights reserved.
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 */

// Initialize Platform
define('PLUTONIUM_VERSION', '0.9.0');

// Initialize Basic Constants
define('DS', DIRECTORY_SEPARATOR);
define('PS', PATH_SEPARATOR);
define('LS', PHP_EOL);
define('BS', '\\');
define('FS', '/');

// Load Configuration Files
require_once 'config.php';
if (is_file('local-config.php'))
	require_once 'local-config.php';

// Initialize Environment Constants
defined('P_ROOT_PATH') or define('P_ROOT_PATH',
	realpath($_SERVER['DOCUMENT_ROOT']));
defined('P_BASE_PATH') or define('P_BASE_PATH',
	realpath(dirname($_SERVER['SCRIPT_FILENAME'])));

$scheme = empty($_SERVER['HTTPS']) ? 'http' : 'https';
$host   = $_SERVER['SERVER_NAME'];
$path   = str_replace(P_ROOT_PATH, '', P_BASE_PATH);
$path   = str_replace(BS, FS, $path);
$path   = trim($path, FS);

defined('P_ROOT_URL') or define('P_ROOT_URL', $scheme . '://' . $host);
defined('P_BASE_URL') or define('P_BASE_URL', P_ROOT_URL . FS . $path);
defined('P_PATH_URL') or define('P_PATH_URL', $path);

require_once 'application/request.php';

unset($scheme, $host, $path);

// Initialize Library
require_once P_BASE_PATH . '/libraries/Plutonium/Functions/Array.php';
require_once P_BASE_PATH . '/libraries/Plutonium/Loader.php';

Plutonium_Loader::autoload(P_BASE_PATH . '/libraries');

$config = new Plutonium_Object($config);

$database =& Plutonium_Database_Helper::getAdapter($config['database']);
$language =& Plutonium_Language::getInstance($config['language']);
$registry =& Plutonium_Registry::getInstance();
$registry->set('config',   $config);
$registry->set('database', $database);
$registry->set('language', $language);

unset($config, $database, $language);

// Initialize Environment
Plutonium_Url::initialize(P_BASE_URL . FS . basename(__FILE__));

$session =& Plutonium_Session::getInstance();
$request =& Plutonium_Request::getInstance();
$request->def('module', 'site');

// Initialize Application
require_once 'application/application.php';
require_once 'application/error.php';

Plutonium_Error_Helper::register(null, 'HttpErrorHandler');

$application =& HttpApplication::getInstance();
$application->initialize();
$application->execute();

?>