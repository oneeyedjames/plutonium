<?php
/**
 * @version   0.1.0
 * @package   Plutonium
 * @author    J Andrew Scott
 * @copyright Copyright (C) 2010 J Andrew Scott. All rights reserved.
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 */

require_once 'constants.php';

if (is_file('config.php'))
	require_once 'config.php';

require_once PU_PATH_BASE . '/libraries/Plutonium/Loader.php';

Plutonium_Loader::autoload(PU_PATH_BASE . '/libraries');
Plutonium_Loader::importDirectory('Plutonium/Functions');

if (!isset($config)) {
	require_once 'setup.php';
	exit;
}

require_once 'application/application.php';
require_once 'application/error.php';

Plutonium_Error_Helper::register(null, 'HttpErrorHandler');

$config = new Plutonium_Object($config);
$config->system->def('scheme', parse_url(PU_URL_BASE, PHP_URL_SCHEME));

Plutonium_Database_Helper::getAdapter($config->database);

Plutonium_Url::initialize(PU_URL_BASE . FS . basename(__FILE__));

$application = new HttpApplication($config);
$application->initialize();
$application->execute();