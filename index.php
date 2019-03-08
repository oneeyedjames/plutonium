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

require_once PU_PATH_BASE . '/libraries/Plutonium/Loader.php';

Plutonium\Loader::autoload(PU_PATH_BASE . '/libraries');
Plutonium\Loader::importDirectory('Plutonium/Functions');

Plutonium\Http\Url::initialize(PU_URL_BASE . FS . basename(__FILE__));

if (isset($config)) {
	require_once 'application/application.php';
	require_once 'application/error.php';

	Plutonium\Error\AbstractHandler::register('HttpErrorHandler');

	$config = new Plutonium\AccessObject($config);
	$config->system->def('scheme', PU_URL_SCHEME);

	Plutonium\Database\AbstractAdapter::getInstance($config->database);

	$application = new HttpApplication($config);
	$application->initialize();
	$application->execute();
} else {
	require_once PU_PATH_BASE . '/application/setup/application.php';
	require_once PU_PATH_BASE . '/application/setup/error.php';

	Plutonium\Error\AbstractHandler::register('SetupErrorHandler');

	$config = new Plutonium\AccessObject(array(
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
