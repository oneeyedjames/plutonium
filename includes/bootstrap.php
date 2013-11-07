<?php

require_once PU_PATH_BASE . '/includes/config.php';

$local_config = PU_PATH_BASE . '/includes/local-config.php';

if (is_file($local_config))
	require_once $local_config;

unset($local_config);

$methods = array('GET', 'POST', 'PUT', 'DELETE');
if (in_array($_REQUEST['_method'], $methods) &&
	$_SERVER['REQUEST_METHOD'] == 'POST')
	$_SERVER['REQUEST_METHOD'] = 'PUT';

$base = array_reverse(explode('.', $config['system']['hostname']));
$host = array_reverse(explode('.', $_SERVER['SERVER_NAME']));
$path = explode(FS, trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), FS));

// TODO Allow for mapped domains
foreach ($base as $base_slug) {
	if ($host[0] == $base_slug || empty($host[0])) array_shift($host);
	else die('Improperly formed hostname');
}

if (!empty($host)) {
	if (isset($host[0])) $_REQUEST['host']   = $host[0];
	if (isset($host[1])) $_REQUEST['module'] = $host[1];
}

if (in_array($path[0], array('', 'index.php')))
	array_shift($path);

if (!empty($path)) {
	$last =& $path[count($path) - 1];

	if (($pos = strrpos($last, '.')) !== false) {
		$_REQUEST['format'] = substr($last, $pos + 1);
		$last = substr($last, 0, $pos);
	}

	$_REQUEST['path'] = implode(FS, $path);

	unset($last, $pos);
}

unset($base, $base_slug, $host, $path);

?>