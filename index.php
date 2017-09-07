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

Plutonium_Url::initialize(PU_URL_BASE . FS . basename(__FILE__));

if (isset($config)) {
	require_once 'application/application.php';
	require_once 'application/error.php';

	Plutonium_Error_Handler::register(null, 'HttpErrorHandler');

	$config = new Plutonium_Object($config);
	$config->system->def('scheme', PU_URL_SCHEME);

	Plutonium_Database_Adapter::getInstance($config->database);

	$application = new HttpApplication($config);
	$application->initialize();
	$application->execute();
} else {
	require_once PU_PATH_BASE . '/application/setup/application.php';
	require_once PU_PATH_BASE . '/application/setup/error.php';

	Plutonium_Error_Handler::register(null, 'SetupErrorHandler');

	$config = new Plutonium_Object(array(
		'system'   => array(
			'hostname' => PU_URL_HOST,
			'scheme'   => PU_URL_SCHEME
		),
		'timezone' => timezone_name_from_abbr('UTC'),
		'locale'   => array('language' => 'en')
	));

	$application = new SetupApplication($config);
	$application->initialize();
	$application->execute();
}
