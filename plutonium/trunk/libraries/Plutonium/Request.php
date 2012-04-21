<?php

class Plutonium_Request implements Plutonium_Accessible {
	protected static $_instance = NULL;
	
	public static function &getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	protected $_uri    = NULL;
	protected $_method = NULL;
	protected $_hashes = array();
	
	protected function __construct() {
		$this->_uri    = $_SERVER['REQUEST_URI'];
		$this->_method = $_SERVER['REQUEST_METHOD'];
		$this->_hashes = array(
			'env'     => $_ENV,
			'server'  => $_SERVER,
			'default' => $_REQUEST,
			'get'     => $_GET,
			'post'    => $_POST,
			'files'   => $_FILES,
			'cookies' => $_COOKIE
		);
	}
	
	public function __get($key) {
		switch ($key) {
			case 'uri':
				return $this->_uri;
			break;
			case 'method':
				return $this->_method;
			break;
			default:
				return $this->get($key);
			break;
		}
	}
	
	public function has($key, $hash = 'default') {
		return isset($this->_hashes[$hash][$key]);
	}
	
	public function get($key, $default = NULL, $hash = 'default') {
		return $this->has($key, $hash) ? $this->_hashes[$hash][$key] : $default;
	}
	
	public function set($key, $value = NULL, $hash = 'default') {
		$this->_hashes[$hash][$key] = $value;
	}
	
	public function def($key, $value = NULL, $hash = 'default') {
		if (!$this->has($key, $hash)) $this->set($key, $value, $hash);
	}
	
	public function del($key, $hash = 'default') {
		unset($this->_hashes[$hash][$key]);
	}
	
	public function toArray($hash = 'default') {
		return isset($this->_hashes[$hash]) ? $this->_hashes[$hash] : NULL;
	}
}

?>