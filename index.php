<?php
/**
 * @version   0.9.0
 * @package   Plutonium
 * @author    J Andrew Scott
 * @copyright Copyright (C) 2010 J Andrew Scott. All rights reserved.
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 */

require_once 'constants.php';
require_once 'bootstrap.php';

// Initialize Library
require_once PU_PATH_BASE . '/libraries/Plutonium/Functions/Array.php';
require_once PU_PATH_BASE . '/libraries/Plutonium/Loader.php';

Plutonium_Loader::autoload(PU_PATH_BASE . '/libraries');

$config = new Plutonium_Object($config);

Plutonium_Database_Helper::getAdapter($config->database);
Plutonium_Language::getInstance($config->language);
Plutonium_Registry::getInstance()->set('config', $config);

unset($config);

// Initialize Environment
Plutonium_Url::initialize(PU_URL_BASE . FS . basename(__FILE__));

Plutonium_Request::getInstance()->def('module', 'site');

// Initialize Application
require_once 'application/application.php';
require_once 'application/error.php';

Plutonium_Error_Helper::register(null, 'HttpErrorHandler');

$application =& HttpApplication::getInstance();
$application->initialize();
$application->execute();

?>