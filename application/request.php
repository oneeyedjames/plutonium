<?php

$uri['host'] = $_SERVER['SERVER_NAME'];
$uri['root'] = dirname($_SERVER['SCRIPT_NAME']);
$uri['self'] = $_SERVER['PHP_SELF'];
$uri['file'] = $_SERVER['SCRIPT_NAME'];
$uri += parse_url($_SERVER['REQUEST_URI']);

$host = explode('.', $_SERVER['SERVER_NAME']);

if ($host[0] == 'www') array_shift($host);

if (count($host) >= 3) $_REQUEST['site'] = $host[count($host) - 3];

if ($uri['self'] != $uri['path'] && $uri['self'] == $uri['file']) {
	$path = trim(str_replace(dirname($uri['file']), '', $uri['path']), '/');
}

if ($uri['self'] == $uri['path'] && $uri['self'] != $uri['file']) {
	$path = trim(str_replace($uri['file'], '', $uri['path']), '/');
}

if (isset($path) && !empty($path)) {
	$args = explode('/', $path);
	
	if (isset($args[0])) $_REQUEST['module']   = $args[0];
	if (isset($args[1])) $_REQUEST['resource'] = $args[1];
	if (isset($args[2])) $_REQUEST['action']   = $args[2];
	
	if (isset($args[3])) {
		if (($pos = strrpos($args[3], '.')) !== FALSE) {
			$_REQUEST['slug']   = substr($args[3], 0, $pos);
			$_REQUEST['format'] = substr($args[3], $pos + 1);
		} else {
			$_REQUEST['slug']   = $args[3];
			$_REQUEST['format'] = 'html';
		}
	}
	
	unset($args, $pos);
}

unset($uri, $host, $path);

?>