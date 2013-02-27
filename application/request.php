<?php

$uri['host'] = $_SERVER['SERVER_NAME'];
$uri['root'] = dirname($_SERVER['SCRIPT_NAME']);
$uri['file'] = $_SERVER['SCRIPT_NAME'];
$uri += parse_url($_SERVER['REQUEST_URI']);

$base = explode('.', $config['system']['hostname']);
$host = explode('.', $_SERVER['SERVER_NAME']);
$path = explode(FS, trim($uri['path'], FS));

if ($host[0] == 'www') array_shift($host);

$host = array_reverse($host);

foreach (array_reverse($base) as $base_slug) {
	if ($host[0] == $base_slug) array_shift($host);
	else die('Improperly formed hostname');
}

if (in_array($path[0], array('', 'index.php'))) array_shift($path);

if (!empty($host)) {
	if (isset($host[0])) $_REQUEST['host']   = $host[0];
	if (isset($host[1])) $_REQUEST['module'] = $host[1];
}

if (!empty($path)) {
	if (isset($path[0])) $_REQUEST['language'] = $path[0];
	if (isset($path[1])) $_REQUEST['resource'] = $path[1];
	if (isset($path[2])) $_REQUEST['action']   = $path[2];
	
	if (isset($path[3])) {
		if (($pos = strrpos($path[3], '.')) !== false) {
			$_REQUEST['slug']   = substr($path[3], 0, $pos);
			$_REQUEST['format'] = substr($path[3], $pos + 1);
		} else {
			$_REQUEST['slug']   = $path[3];
			$_REQUEST['format'] = 'html';
		}
		
		unset($pos);
	}
}

header('Content-type: text/plain');
die(print_r($_REQUEST, true));

unset($uri, $base, $host, $path);

?>