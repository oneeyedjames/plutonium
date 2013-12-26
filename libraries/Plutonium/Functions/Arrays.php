<?php

function is_assoc($var) {
	if (!is_array($var) || empty($var)) return false;

	foreach (array_keys($var) as $key)
		if (is_string($key)) return true;

	return false;
}

function is_range($var) {
	if (!is_array($var) || empty($var)) return false;

	foreach (array_keys($var) as $key)
		if (!in_array($key, array('min', 'max'))) return false;

	return true;
}

function array_peek(&$array) {
	$values = array_values($array);
	return $values[count($array) - 1];
}

?>