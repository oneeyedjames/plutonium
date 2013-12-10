<?php
/**
 * @version   0.1.0
 * @package   Plutonium
 * @author    J Andrew Scott
 * @copyright Copyright (C) 2010 J Andrew Scott. All rights reserved.
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 */

if (isset($config)) exit;

$const = get_defined_constants(true);

ksort($const['user']);

header('Content-type: text/plain');
print_r($const['user']);

/*
 * TODO
 * Autoload library functions/classes
 * Bootstrap application object
 *

$database = Plutonium_Database_Helper::getAdapter($config->database);

Plutonium_Database_Helper::getTable('hosts');
Plutonium_Database_Helper::getTable('users');
Plutonium_Database_Helper::getTable('groups');

Plutonium_Database_Helper::getTable('themes');
Plutonium_Database_Helper::getTable('modules');
Plutonium_Database_Helper::getTable('widgets')->find(1)->module(array());

 */

?>