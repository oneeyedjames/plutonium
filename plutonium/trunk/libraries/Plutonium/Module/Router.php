<?php

class Plutonium_Module_Router {
	public static function getInstance($config) {
		$type = 'Plutonium_Model_Router_' . $config->driver;
		
		return new $type($config);
	}
	
	protected $_config = null;
	protected $_params = array();
	protected $_values = array();
	
	protected $_defaults = array();
	
	public function __construct($config) {
		$this->_config = $config;
		
		$this->_params   = array_keys($config->defaults);
		$this->_defaults = $config->defaults;
	}
	
	public function getParams() {
		return $this->_values + $this->_defaults;
	}
	
	public function getParam($key) {
		if (isset($this->_values[$key]))   return $this->_values[$key];
		if (isset($this->_defaults[$key])) return $this->_defaults[$key];
		
		return null;
	}
	
	public function match($request) {
	}
	
	public function build($params) {
		return 'index.php?' . http_build_query($params);
	}
	
	public function parse($url) {
	}
}

?>