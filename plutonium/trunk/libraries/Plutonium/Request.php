<?php

class Plutonium_Request implements Plutonium_Accessible {
	protected static $_instance = null;

	public static function &getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new self();

		return self::$_instance;
	}

	protected $_uri    = null;
	protected $_method = null;
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

		$this->_initMethod();
		$this->_initHost();
		$this->_initPath();
	}

	protected function _initMethod() {
		$method = $this->get('_method', null, 'post');

		switch ($this->method) {
			case 'POST':
				if (in_array($method, array('PUT', 'DELETE')))
					$this->_method = $method;
				break;
			case 'PUT':
				parse_str(file_get_contents('php://input', $query));
				$this->_hashes['default'] = array_merge_recursive($_REQUEST, $query);
				break;
		}
	}

	protected function _initHost() {
		$registry =& Plutonium_Registry::getInstance();

		$base = $registry->config->system->hostname;
		$host = $this->get('SERVER_NAME', null, 'server');

		$base = array_reverse(explode('.', $base));
		$host = array_reverse(explode('.', $host));

		// TODO Support mapped domains
		foreach ($base as $base_slug) {
			if ($host[0] == $base_slug || empty($host[0])) array_shift($host);
			else die('Improperly formed hostname');
		}

		if (!empty($host)) {
			if (isset($host[0])) $this->set('host',   $host[0]);
			if (isset($host[1])) $this->set('module', $host[1]);
		}
	}

	protected function _initPath() {
		$path = explode(FS, trim(parse_url($this->uri, PHP_URL_PATH), FS));

		if (!empty($path)) {
			$last = array_pop($path);

			if (($pos = strrpos($last, '.')) !== false) {
				$this->set('format', substr($last, $pos + 1));
				array_push($path, substr($last, 0, $pos));
			}

			$this->set('path', implode(FS, $path));
		}
	}

	public function __get($key) {
		switch ($key) {
			case 'uri':
				return $this->_uri;
			case 'method':
				return $this->_method;
			default:
				return $this->get($key);
		}
	}

	public function __isset($key) {
		switch ($key) {
			case 'uri':
			case 'method':
				return true;
			default:
				return $this->has($key);
		}
	}

	public function has($key, $hash = 'default') {
		return isset($this->_hashes[$hash][$key]);
	}

	public function get($key, $default = null, $hash = 'default') {
		return $this->has($key, $hash) ? $this->_hashes[$hash][$key] : $default;
	}

	public function set($key, $value = null, $hash = 'default') {
		$this->_hashes[$hash][$key] = $value;
	}

	public function def($key, $value = null, $hash = 'default') {
		if (!$this->has($key, $hash)) $this->set($key, $value, $hash);
	}

	public function del($key, $hash = 'default') {
		unset($this->_hashes[$hash][$key]);
	}

	public function toArray($hash = 'default') {
		return isset($this->_hashes[$hash]) ? $this->_hashes[$hash] : null;
	}
}

?>