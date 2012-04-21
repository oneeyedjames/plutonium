<?php
/**
 * @version		0.9.0
 * @package		Plutonium
 * @author		J Andrew Scott
 * @copyright	Copyright (C) 2010 J Andrew Scott. All rights reserved.
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 */

require_once 'framework.php';
require_once 'functions.php';

// Initialize Components
require_once 'application/request.php';
require_once 'application/config.php';

$config = new Plutonium_Object($config);

$database =& Plutonium_Database_Helper::getAdapter($config['database']);
$language =& Plutonium_Language_Helper::getLanguage($config['language']);

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

Plutonium_Error_Helper::register(NULL, 'HttpErrorHandler');

$application =& HttpApplication::getInstance();
$application->initialize();
$application->execute();

?>