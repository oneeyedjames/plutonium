<?php

class Plutonium_Application_Helper {
	protected static $_path = null;
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('P_BASE_PATH')) {
			self::$_path = realpath(P_BASE_PATH . '/application');
		}
		
		return self::$_path;
	}
	
	public static function setPath($path) {
		self::$_path = $path;
	}
}

?>