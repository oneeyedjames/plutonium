<?php

if (isset($config)) exit;

if (!defined(PU_PATH_BASE)) {
	require_once 'constants.php';
	require_once PU_PATH_BASE . '/libraries/Plutonium/Loader.php';

	Plutonium_Loader::autoload(PU_PATH_BASE . '/libraries');
	Plutonium_Loader::importDirectory('Plutonium/Functions');
}

require_once PU_PATH_BASE . '/application/setup/application.php';
require_once PU_PATH_BASE . '/application/setup/error.php';

Plutonium_Error_Helper::register(null, 'SetupErrorHandler');

$config = new Plutonium_Object(array(
	'system'   => array('hostname' => $_SERVER['SERVER_NAME']),
	'location' => array('timezone' => timezone_name_from_abbr('UTC')),
	'language' => array('code' => 'en')
));

$application = new SetupApplication($config);
$application->initialize();
$application->execute();

/* TODO Autoload library functions/classes

$database = Plutonium_Database_Helper::getAdapter($config->database);

Plutonium_Database_Helper::getTable('hosts');
Plutonium_Database_Helper::getTable('users');
Plutonium_Database_Helper::getTable('groups');

Plutonium_Database_Helper::getTable('themes');
Plutonium_Database_Helper::getTable('modules');
Plutonium_Database_Helper::getTable('widgets')->find(1)->module(array());

 */

?>