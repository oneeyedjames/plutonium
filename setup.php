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

Plutonium_Error_Handler::register(null, 'SetupErrorHandler');

$config = new Plutonium_Object(array(
	'system'   => array('hostname' => $_SERVER['SERVER_NAME']),
	'timezone' => timezone_name_from_abbr('UTC'),
	'locale'   => array('language' => 'en')
));

$application = new SetupApplication($config);
$application->initialize();
$application->execute();

/* TODO Autoload library functions/classes */

$database = Plutonium_Database_Adapter::getInstance($config->database);

Plutonium_Database_Table::getInstance('hosts');
// Plutonium_Database_Table::getInstance('domains');
// Plutonium_Database_Table::getInstance('users');
// Plutonium_Database_Table::getInstance('groups');
//
// Plutonium_Database_Table::getInstance('themes');
// Plutonium_Database_Table::getInstance('modules');
// Plutonium_Database_Table::getInstance('widgets')->find(1)->module(array());
