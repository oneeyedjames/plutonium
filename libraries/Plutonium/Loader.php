<?php

class Plutonium_Loader {
	private static $_instance = null;
	
	public static function &getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new self();
		
		return self::$_instance;
	}
	
	public static function getClass($file, $class, $default, $args = null) {
		if (is_file($file)) require_once $file;
		
		$type = class_exists($class) ? $class : $default;
		
		return is_null($args) new $type() : new $type($args);
	}
	
	public static function load($class) {
		self::getInstance()->import($class);
	}
	
	public static function autoload($path = null) {
		self::getInstance()->addPath($path);
	}
	
	protected function __construct() {
		spl_autoload_register(array($this, 'import'));
	}
	
	public function addPath($path) {
		set_include_path(get_include_path() . PS . realpath($path));
	}
	
	public function addExtension($extension) {
		spl_autoload_extensions(spl_autoload_extensions() . ',' . $extension);
	}
	
	public function import($class) {
		$paths      = explode(PS,  get_include_path());
		$extensions = explode(',', spl_autoload_extensions());
		
		$classpath = str_replace('_', DS, $class);
		
		foreach ($paths as $path) {
			foreach ($extensions as $extension) {
				$filename = $path . DS . $classpath . $extension;
				if (is_file($filename)) {
					require_once $filename;
					return true;
				}
			}
		}
		
		return false;
	}
}

?>