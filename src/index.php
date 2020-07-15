<?php
/**
 * @version   0.1.0
 * @package   Plutonium
 * @author    J Andrew Scott
 * @copyright Copyright (C) 2010 J Andrew Scott. All rights reserved.
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 */

if ('/favicon.ico' == $_SERVER['REQUEST_URI']) exit;

require_once 'constants.php';

if (is_file(PU_PATH_BASE . '/config.php'))
	require_once PU_PATH_BASE . '/config.php';

if (empty($config)) {
	http_response_code(500);
	die('Configuration could not be loaded.');
}

if ($vendor_path = realpath(dirname(PU_PATH_BASE) . '/vendor'))
	require_once $vendor_path . DS . 'autoload.php';

unset($vendor_path);

Plutonium\Loader::autoload();
Plutonium\Loader::importDirectory('Plutonium/Functions');

Plutonium\Http\Url::initialize(PU_URL_BASE . FS . basename(__FILE__));

require_once 'application/application.php';
require_once 'application/error.php';

Plutonium\Error\ErrorHandler::register('HttpErrorHandler');

$config = new Plutonium\AccessObject($config);
$config->system->def('scheme', PU_URL_SCHEME);
$config->system->def('default_host', 'main');
$config->application->def('default_theme', 'charcoal');
$config->application->def('default_module', 'site');

Plutonium\Database\AbstractAdapter::getInstance($config->database);

$application = new HttpApplication($config);
$application->initialize();
$application->execute();
