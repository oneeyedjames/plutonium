<?php

class Plutonium_Theme {
	protected static $_path = null;
	
	protected static $_instance = null;
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/themes');
		
		return self::$_path;
	}
	
	public static function setPath($path) {
		self::$_path = $path;
	}
	
	public static function getInstance($name) {
		if (is_null(self::$_instance) && !is_null($name)) {
			$name = strtolower($name);
			$file = self::getPath() . DS . $name . DS . 'theme.php';
			$type = ucfirst($name) . 'Theme';
		
			self::$_instance = Plutonium_Loader::getClass($file, $type, __CLASS__, $name);
		}
		
		return self::$_instance;
	}
	
	protected $_name   = null;
	protected $_layout = null;
	protected $_format = null;
	protected $_params = null;
	protected $_output = null;
	
	public function __construct($name) {
		$this->_name   = $name;
		$this->_layout = 'default';
		$this->_format = 'html';
		$this->_params = new Plutonium_Object();
	}
	
	public function hasWidgets($location) {
		return $this->countWidgets($location) > 0;
	}
	
	public function countWidgets($location) {
		$response =& Plutonium_Response::getInstance();
		
		return $response->getWidgetCount($location);
	}
	
	public function display() {
		$request =& Plutonium_Request::getInstance();
		
		$name   = strtolower($this->_name);
		$layout = strtolower($request->get('layout', $this->_layout));
		$format = strtolower($request->get('format', $this->_format));
		
		$file = self::getPath() . DS . $name . DS
		      . 'layouts' . DS . $layout . '.' . $format . '.php';
		
		if (is_file($file)) {
			ob_start();
			
			include $file;
			
			$this->_output = ob_get_contents();
			
			ob_end_clean();
		} else {
			// TODO raise error
		}
		
		return $this->_output;
	}
}

?>