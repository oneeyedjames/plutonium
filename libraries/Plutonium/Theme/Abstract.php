<?php

abstract class Plutonium_Theme_Abstract {
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
	
	public function countWidgets($location) {
		$response =& Plutonium_Response::getInstance();
		
		return $response->getWidgetCount($location);
	}
	
	public function display() {
		$request =& Plutonium_Request::getInstance();
		
		$name   = strtolower($this->_name);
		$layout = strtolower($request->get('layout', $this->_layout));
		$format = strtolower($request->get('format', $this->_format));
		
		$file = Plutonium_Theme_Helper::getPath() . DS . $name . DS
		      . 'layouts' . DS . $layout . '.' . $format . '.php';
		
		if (is_file($file)) {
			ob_start();
			
			include $file;
			
			$this->_output = ob_get_contents();
			
			ob_end_clean();
		} else {
			// raise error
		}
		
		return $this->_output;
	}
}

?>