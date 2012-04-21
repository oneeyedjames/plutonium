<?php

class Plutonium_Url extends Plutonium_Object {
	protected static $_scheme = NULL;
	protected static $_host   = NULL;
	protected static $_path   = NULL;
	
	public static function initialize($base_url) {
		$parts = parse_url($base_url);
		
		if (isset($parts['scheme'])) self::$_scheme = $parts['scheme'];
		if (isset($parts['host']))   self::$_host   = $parts['host'];
		if (isset($parts['path']))   self::$_path   = trim($parts['path'], '/');
	}
	
	public static function getInstance() {
		$request =& Plutonium_Request::getInstance();
		
		$vars = $request->toArray('post') + $request->toArray('get');
		
		return new self($vars);
	}
	
	public function __construct($vars = NULL) {
		parent::__construct($vars);
	}
	
	public function query() {
		return empty($this->_vars) ? '' : '?'
			 . http_build_query($this->_vars);
	}
	
	public function toString() {
		$url = self::$_scheme . '://' . self::$_host . '/' . self::$_path;
		
		if ($this->has('module'))   $url .= '/' . $this->get('module');
		if ($this->has('resource')) $url .= '/' . $this->get('resource');
		if ($this->has('action'))   $url .= '/' . $this->get('action');
		if ($this->has('slug'))     $url .= '/' . $this->get('slug');
		if ($this->has('format'))   $url .= '.' . $this->get('format');
		
		return $url;
	}
}

?>