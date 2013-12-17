<?php

final class Plutonium_Loader {
	private static $_registered = false;

	private static function register() {
		if (!self::$_registered)
			spl_autoload_register(array(__CLASS__, 'import'));
	}

	public static function addPath($path) {
		set_include_path(get_include_path() . PATH_SEPARATOR . realpath($path));
	}

	public static function addExtension($extension) {
		spl_autoload_extensions(spl_autoload_extensions() . ',' . $extension);
	}

	public static function getClass($file, $class, $default, $args = null) {
		if (is_file($file)) require_once $file;

		$type = class_exists($class) ? $class : $default;

		return is_null($args) ? new $type() : new $type($args);
	}

	public static function load($class) {
		self::register();
		self::import($class);
	}

	public static function autoload($path = null) {
		self::register();
		self::addPath($path);
	}

	public static function import($class) {
		$paths      = explode(PATH_SEPARATOR,  get_include_path());
		$extensions = explode(',', spl_autoload_extensions());

		$classpath = str_replace('_', DIRECTORY_SEPARATOR, $class);

		foreach ($paths as $path) {
			foreach ($extensions as $extension) {
				$filename = $path . DIRECTORY_SEPARATOR . $classpath . $extension;
				if (is_file($filename)) {
					require_once $filename;
					return $classpath;
				}
			}
		}

		return false;
	}
}

?>