<?php

$path = dirname(__DIR__);
$file = implode(DIRECTORY_SEPARATOR, [$path, 'src', 'config.php']);

if (is_file($file)) die("$file already exists." . PHP_EOL);

list($lang, $charset) = explode('.', $_SERVER['LANG'], 2);

$locale = str_replace('_', '-', $lang);
$timezone = date_default_timezone_get();

$config = [];
$config['system']['hostname'] = get_input('WWW Hostname');

$config['database']['driver']   = get_input('Database Driver', 'MySQL');
$config['database']['hostname'] = get_input('Database Host', 'localhost');
$config['database']['dbname']   = get_input('Database Name', 'plutonium');
$config['database']['username'] = get_input('Database Username');
$config['database']['password'] = get_input('Database Password', '', true);

$config['locale']   = get_input('Locale', $locale);
$config['timezone'] = get_input('Timezone', $timezone);

file_put_contents($file, '<?php' . PHP_EOL . PHP_EOL . build_config($config));

function build_config($value, $key = []) {
	$output = '';

	if (is_scalar($value)) {
		$output = '$config';

		foreach ($key as $subkey)
			$output .= "['$subkey']";

		$output .= " = '$value';";
	} elseif (is_array($value)) {
		foreach ($value as $subkey => $subvalue)
			$output .= build_config($subvalue, array_merge($key, [$subkey]));
	}

	return $output . PHP_EOL;
}
