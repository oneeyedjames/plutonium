<?php

class Plutonium_Theme {
	protected static $_path = null;
	
	protected static $_instance = null;
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/themes');
		
		return self::$_path;
	}
	
	public static function getName() {
		return is_null(self::$_instance) ? null : self::$_instance->name;
	}
	
	public static function &getInstance($name = null) {
		if (is_null(self::$_instance) && !is_null($name)) {
			$name = strtolower($name);
			$type = ucfirst($name) . 'Theme';
			$file = self::getPath() . DS . $name . DS . 'theme.php';
			
			self::$_instance = Plutonium_Loader::getClass($file, $type, __CLASS__, $name);
			
			$language =& Plutonium_Language::getInstance();
			$language->load($name, 'themes');
		}
		
		return self::$_instance;
	}
	
	protected $_name   = null;
	protected $_layout = null;
	protected $_format = null;
	protected $_params = null;
	protected $_output = null;
	
	protected $_message_start = '<div class="pu-message">';
	protected $_message_close = '</div>';
	
	protected $_module_start = '<div class="pu-module">';
	protected $_module_close = '</div>';
	
	protected $_widget_start = '<div class="pu-widget">';
	protected $_widget_close = '</div>';
	protected $_widget_delim = LS;
	
	public function __construct($name) {
		$this->_name   = $name;
		$this->_layout = 'default';
		$this->_format = 'html';
		$this->_params = new Plutonium_Object();
	}
	
	public function __get($key) {
		switch ($key) {
			case 'message_start':
				return $this->_message_start;
			case 'message_close':
				return $this->_message_close;
			case 'module_start':
				return $this->_module_start;
			case 'module_close':
				return $this->_module_close;
			case 'widget_start':
				return $this->_widget_start;
			case 'widget_close':
				return $this->_widget_close;
			case 'widget_delim':
				return $this->_widget_delim;
		}
	}
	
	public function hasWidgets($location) {
		return $this->countWidgets($location) > 0;
	}
	
	public function countWidgets($location) {
		return Plutonium_Response::getInstance()->getWidgetCount($location);
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