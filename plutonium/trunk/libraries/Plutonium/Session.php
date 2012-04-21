<?php

class Plutonium_Session implements Plutonium_Accessible {
	protected static $_instance = NULL;
	
	public static function &getInstance() {
		if (is_null(self::$_instance)) {
			session_start();
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	protected $_namespaces = array();
	
	protected function __construct() {
		$this->_namespaces =& $_SESSION;
	}
	
	public function has($key, $namespace = 'default') {
		return isset($this->_namespaces[$namespace][$key]);
	}
	
	public function get($key, $default = NULL, $namespace = 'default') {
		return $this->has($key, $namespace) ? $this->_namespaces[$namespace][$key] : $default;
	}
	
	public function set($key, $value = NULL, $namespace = 'default') {
		$this->_namespaces[$namespace][$key] = $value;
	}
	
	public function def($key, $value = NULL, $namespace = 'default') {
		if (!$this->has($key, $namespace)) $this->set($key, $value, $namespace);
	}
	
	public function del($key, $namespace = 'default') {
		unset($this->_namespaces[$namespace][$key]);
	}
	
	public function toArray($namespace = 'default') {
		return isset($this->_namespaces[$namespace]) ? $this->_namespaces[$namespace] : array();
	}
}

?>