<?php

class Plutonium_Widget_Helper {
	protected static $_path = null;
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('P_BASE_PATH')) {
			self::$_path = realpath(P_BASE_PATH . '/widgets');
		}
		
		return self::$_path;
	}
	
	public static function setPath($path) {
		self::$_path = $path;
	}
	
	public static function getWidget($name) {
		$name = strtolower($name);
		$file = self::getPath() . DS . $name . DS . 'widget.php';
		$type = ucfirst($name) . 'Widget';
		
		if (is_file($file)) {
			require_once $file;
			return new $type($name);
		}
		
		return new Plutonium_Widget_Default($name);
	}
}

?>