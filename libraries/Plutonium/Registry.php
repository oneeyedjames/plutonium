<?php

class Plutonium_Registry extends Plutonium_Object_Filter {
	protected static $_instance = null;

	public static function &getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new self();

		return self::$_instance;
	}

	public function loadFile($file, $format = 'xml') {
		if (is_file($file)) {
			$data = file_get_contents($file);
			$this->loadString($data, $format);
		}
	}

	public function loadString($data, $format = 'xml') {
		switch ($format) {
			case 'xml':
				$this->loadXML($data);
				break;
		}
	}

	public function loadXML($data) {
		$doc = new DOMDocument();
		$doc->loadXML($data);
	}

	// Export methods
	public function toArray() {
		return $this->_vars;
	}

	public function toObject() {
		return (object) $this->_vars;
	}

	public function toString($format = 'uri') {
		$args = array();

		foreach ($this->_vars as $key => $value) {
			$args[] = urlencode($key) . '=' . urlencode($value);
		}

		$str = implode('&', $args);

		return $str;
	}

	// Import factory Methods
	public static function fromArray($arr) {
		if (is_array($arr)) {
			$obj = new self();
			$obj->_vars = $arr;

			return $obj;
		}

		return null;
	}

	public static function fromObject($obj) {
		if (is_object($obj)) {
			$vars = get_object_vars($obj);

			$obj = new self();
			$obj->_vars = $vars;

			return $obj;
		}

		return null;
	}

	public static function fromString($str) {
		if (is_string($str)) {
			$args = explode('&', $str);

			$obj = new self();

			foreach ($args as $arg) {
				@list($key, $value) = explode('=', $arg);

				$obj->set(urldecode($key), urldecode($value));
			}

			return $obj;
		}

		return null;
	}
}

?>