<?php

class Plutonium_Widget {
	protected static $_path = null;
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/widgets');
		
		return self::$_path;
	}
	
	public static function setPath($path) {
		self::$_path = $path;
	}
	
	public static function getInstance($name) {
		$name = strtolower($name);
		$file = self::getPath() . DS . $name . DS . 'widget.php';
		$type = ucfirst($name) . 'Widget';
		
		return Plutonium_Loader::getClass($file, $type, __CLASS__, $name);
	}
	
	protected $_name   = null;
	protected $_vars   = null;
	protected $_layout = null;
	protected $_format = null;
	protected $_params = null;
	protected $_output = null;
	
	public function __construct($name, $params = null) {
		$this->_name   = $name;
		$this->_vars   = array();
		$this->_layout = 'default';
		$this->_format = 'html';
		$this->_params = is_a($params, 'Plutonium_Object') ? $params
					   : new Plutonium_Object($params);
	}
	
	public function __get($key) {
		return $this->getVar($key);
	}
	
	public function __set($key, $value) {
		$this->setVal($key, $value);
	}
	
	public function getVar($key) {
		return $this->_vars[$key];
	}
	
	public function setVal($key, $var) {
		$this->_vars[$key] = $var;
	}
	
	public function setRef($key, &$var) {
		$this->_vars[$key] = $var;
	}
	
	public function display() {
		$request =& Plutonium_Request::getInstance();
		
		$name   = strtolower($this->_name);
		$layout = strtolower($this->_layout);
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