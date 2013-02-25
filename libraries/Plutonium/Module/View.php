<?php

class Plutonium_Module_View {
	protected $_name   = null;
	protected $_vars   = null;
	protected $_layout = null;
	protected $_format = null;
	protected $_output = null;
	protected $_module = null;
	
	public function __construct($name, $module) {
		$this->_name = $name;
		$this->_vars = array();
		
		$request =& Plutonium_Request::getInstance();
		
		$this->_layout = $request->get('action', 'default');
		$this->_format = $request->get('format', 'html');
		
		$this->_module = $module;
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
	
	public function getLayout() {
		return $this->_layout;
	}
	
	public function setLayout($layout) {
		$this->_layout = $layout;
	}
	
	public function getFormat() {
		return $this->_layout;
	}
	
	public function setFormat($format) {
		$this->_format = $format;
	}
	
	public function &getModel($name = null) {
		return $this->_module->getModel($name);
	}
	
	public function display() {
		$path   = $this->_module->getPath();
		$module = $this->_module->getName();
		
		$name   = strtolower($this->_name);
		$layout = strtolower($this->_layout);
		$format = strtolower($this->_format);
		
		$method = $layout . 'Layout';
		
		if (method_exists($this, $method))
			call_user_func(array($this, $method));
		
		$file = $path . DS . $module . DS . 'views' . DS . $name . DS
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