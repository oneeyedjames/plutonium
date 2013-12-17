<?php

class Plutonium_Request implements Plutonium_Accessible {
	protected $_uri    = null;
	protected $_method = null;
	protected $_hashes = array();

	public function __construct($config) {
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

		$this->_initMethod($this->get('_method', 'GET'));
		$this->_initHost($config->hostname);
		$this->_initPath(parse_url($this->_uri, PHP_URL_PATH));
	}

	protected function _initMethod($method) {
		switch ($this->_method) {
			case 'POST':
				if (in_array($method, array('PUT', 'DELETE'))) {
					$this->_method = $method;
					$this->del('_method');
				}
				break;
			case 'PUT':
				parse_str(file_get_contents('php://input', $query));
				$this->_hashes['default'] = array_merge_recursive($_REQUEST, $query);
				break;
		}
	}

	protected function _initHost($host) {
		$base = $host;
		$host = $this->get('SERVER_NAME', null, 'server');

		$base = array_reverse(explode('.', trim($base)));
		$host = array_reverse(explode('.', trim($host)));

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

	protected function _initPath($path) {
		$path = explode(FS, trim(parse_url($this->uri, PHP_URL_PATH), FS));

		if (!empty($path)) {
			$last =& $path[count($path) - 1];

			if (($pos = strrpos($last, '.')) !== false) {
				$this->format = substr($last, $pos + 1);
				$last = substr($last, 0, $pos);
			}

			$this->path = implode(FS, $path);
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

	public function __set($key, $value) {
		switch ($key) {
			case 'uri':
			case 'method':
				break;
			default:
				$this->set($key, $value);
				break;
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

	public function __unset($key) {
		switch ($key) {
			case 'uri':
			case 'method':
				break;
			default:
				$this->del($key);
				break;
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