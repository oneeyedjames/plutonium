<?php

require_once 'config.php';
if (is_file('local-config.php')) require_once 'local-config.php';

$base = array_reverse(explode('.', $config['system']['hostname']));
$host = array_reverse(explode('.', $_SERVER['SERVER_NAME']));
$path = explode(FS, parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

foreach ($base as $base_slug) {
	if ($host[0] == $base_slug || empty($host[0])) array_shift($host);
	else die('Improperly formed hostname');
}

if (in_array($path[0], array('', 'index.php'))) array_shift($path);

if (!empty($host)) {
	if (isset($host[0])) $_REQUEST['host']   = $host[0];
	if (isset($host[1])) $_REQUEST['module'] = $host[1];
}

if (!empty($path)) {
	$last =& $path[count($path) - 1];
	
	if (($pos = strrpos($last, '.')) !== false) {
		$_REQUEST['format'] = substr($last, $pos + 1);
		$last = substr($last, 0, $pos);
	}
	
	$_REQUEST['path'] = $path;
	
	unset($last, $pos);
}

unset($host, $path, $base, $base_slug);

?>