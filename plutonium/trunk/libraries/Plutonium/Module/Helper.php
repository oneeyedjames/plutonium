<?php

class Plutonium_Module_Helper {
	protected static $_name = NULL;
	protected static $_path = NULL;
	
	protected static $_module     = NULL;
	protected static $_controller = NULL;
	protected static $_models     = array();
	protected static $_view       = NULL;
	
	public static function getName() {
		return self::$_name;
	}
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('P_BASE_PATH')) {
			self::$_path = realpath(P_BASE_PATH . '/modules');
		}
		
		return self::$_path;
	}
	
	public static function setPath($path) {
		self::$_path = $path;
	}
	
	public static function &getModule($name) {
		$name = strtolower($name);
		self::$_name = $name;
		
		if (is_null(self::$_module)) {
			$type = ucfirst($name) . 'Module';
			$file = self::getPath() . DS . $name . DS . 'module.php';
			
			if (is_file($file)) require_once $file;
			
			$type = class_exists($type) ? $type
				  : 'Plutonium_Module_Default';
			
			self::$_module = new $type($name);
			
			$language =& Plutonium_Language_Helper::getLanguage();
			$language->load(self::getPath() . DS . $name . DS . 'languages');
		}
		
		return self::$_module;
	}
	
	public static function &getController($name) {
		$name = strtolower($name);
		
		if (is_null(self::$_controller)) {
			$type = ucfirst($name) . 'Controller';
			$file = self::getPath() . DS . self::$_name
				  . DS . 'controllers' . DS . $name . '.php';
			
			if (is_file($file)) require_once $file;
			
			$type = class_exists($type) ? $type
				  : 'Plutonium_Module_Controller_Default';
			
			self::$_controller = new $type($name);
		}
		
		return self::$_controller;
	}
	
	public static function &getModel($name) {
		$name = strtolower($name);
		
		if (empty(self::$_models[$name])) {
			$type = ucfirst($name) . 'Model';
			$file = self::getPath() . DS . self::$_name
				  . DS . 'models' . DS . $name . '.php';
			
			if (is_file($file)) require_once $file;
			
			$type = class_exists($type) ? $type
				  : 'Plutonium_Module_Model_Default';
			
			self::$_models[$name] = new $type($name);
		}
		
		return self::$_models[$name];
	}
	
	public static function &getView($name) {
		$name = strtolower($name);
		
		if (is_null(self::$_view)) {
			$type = ucfirst($name) . 'View';
			$file = self::getPath() . DS . self::$_name . DS
				  . 'views' . DS . $name . DS . 'view.php';
			
			if (is_file($file)) require_once $file;
			
			$type = class_exists($type) ? $type
				  : 'Plutonium_Module_View_Default';
			
			self::$_view = new $type($name);
		}
		
		return self::$_view;
	}
}

?>