<?php

class Plutonium_Module_Router_Abstract {
	protected $_config = NULL;
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
		
		return NULL;
	}
	
	abstract public function match($request);
	
	abstract public function build($params);
}

?>