<?php
/**
 * @version   0.9.0
 * @package   Plutonium
 * @author    J Andrew Scott
 * @copyright Copyright (C) 2010 J Andrew Scott. All rights reserved.
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 */

if (is_file('includes/config.php'))
	return;

require_once 'includes/constants.php';

$const = get_defined_constants(true);

ksort($const['user']);

header('Content-type: text/plain');
print_r($const['user']);

/* require_once 'config.php';

if (is_file('local-config.php')) require_once 'local-config.php';

require_once PU_PATH_BASE . '/libraries/Plutonium/Functions/Array.php';
require_once PU_PATH_BASE . '/libraries/Plutonium/Loader.php';

Plutonium_Loader::autoload(PU_PATH_BASE . '/libraries');

$config = new Plutonium_Object($config);

$database = Plutonium_Database_Helper::getAdapter($config->database);

Plutonium_Database_Helper::getTable('hosts');
Plutonium_Database_Helper::getTable('users');
Plutonium_Database_Helper::getTable('groups');

Plutonium_Database_Helper::getTable('themes');
Plutonium_Database_Helper::getTable('modules');
Plutonium_Database_Helper::getTable('widgets')->find(1)->module(array()); */

?>