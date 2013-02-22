<?php
/**
 * @version		0.9.0
 * @package		Plutonium
 * @author		J Andrew Scott
 * @copyright	Copyright (C) 2010 J Andrew Scott. All rights reserved.
 * @license		http://opensource.org/licenses/gpl-license.php GNU Public License
 */

function is_assoc($var) {
	if (!is_array($var) || empty($var)) return false;
	
	foreach (array_keys($var) as $key) {
		if (is_string($key)) return true;
	}
	
	return false;
}

function is_range($var) {
	if (!is_array($var) || empty($var)) return false;
	
	foreach (array_keys($var) as $key) {
		if (!in_array($key, array('min', 'max'))) return false;
	}
	
	return true;
}

?>