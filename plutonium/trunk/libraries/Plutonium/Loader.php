<?php

class Plutonium_Loader {
	private static $_instance = NULL;
	
	public static function &getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	public static function load($class) {
		self::getInstance()->import($class);
	}
	
	public static function autoload($path = NULL) {
		self::getInstance()->addPath($path);
	}
	
	protected function __construct() {
		spl_autoload_register(array($this, 'import'));
	}
	
	public function addPath($path) {
		$paths  = get_include_path();
		$paths .= PS . realpath($path);
		
		set_include_path($paths);
	}
	
	public function addExtension($extension) {
		$extensions  = spl_autoload_extensions();
		$extensions .= ',' . $extension;
		
		spl_autoload_extensions($extensions);
	}
	
	public function import($class) {
		$paths = get_include_path();
		$paths = explode(PS, $paths);
		
		$extensions = spl_autoload_extensions();
		$extensions = explode(',', $extensions);
		
		$basepath = str_replace('_', DS, $class);
		
		foreach ($paths as $path) {
			foreach ($extensions as $extension) {
				$filename = $path . DS . $basepath . $extension;
				if (is_file($filename)) require_once $filename;
			}
		}
	}
}

?>