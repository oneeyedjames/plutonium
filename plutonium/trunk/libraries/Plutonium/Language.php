<?php

class Plutonium_Language {
	protected static $_path = null;

	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/languages');

		return self::$_path;
	}

	protected $_name;
	protected $_code;
	protected $_locale;
	protected $_phrases;

	public function __construct($config) {
		$code   = $config->code;
		$locale = $config->locale;

		if (strpos($code, '-') !== false) {
			if (is_null($locale))
				list($code, $locale) = explode('-', $code);
			else
				list($code, $null) = explode('-', $code);
		}

		$this->_code   = $config->code   = strtolower($code);
		$this->_locale = $config->locale = strtoupper($locale);

		$this->_phrases = array();

		if ($path = $this->_getPath($code)) {
			$this->_loadFile($path . DS . 'language.xml');
		} else {
			$message = sprintf("Could not find language resource: %s.", $code);
			trigger_error($message, E_USER_WARNING);
		}

		if (!empty($locale)) {
			if ($path = $this->_getPath($code, $locale)) {
				$this->_loadFile($path . DS . 'language.xml');
			} else {
				$message = sprintf("Could not find language resource: %s-%s.", $code, $locale);
				trigger_error($message, E_USER_NOTICE);
			}
		}
	}

	public function __get($key) {
		switch ($key) {
			case 'name':
				$name = $this->_code;

				if (!empty($this->_locale))
					$name .= '-' . $this->_locale;

				return $name;
			case 'code':
				return $this->_code;
			case 'locale':
				return $this->_locale;
		}
	}

	public function load($name, $type) {
		$name = strtolower($name);
		$type = strtolower($type);

		switch ($type) {
			case 'themes':
			case 'modules':
			case 'widgets':
				$path = self::getPath() . DS . $this->code . DS . $type;
				$file = $path . DS . $name . '.xml';

				if (!$this->_loadFile($file)) {
					$message = sprintf("Resource does not exist: %s", $file);
					trigger_error($message, E_USER_WARNING);
				}

				if (!empty($this->locale)) {
					$path = self::getPath() . DS . $this->name . DS . $type;
					$file = $path . DS . $name . '.xml';

					if (!$this->_loadFile($file)) {
						$message = sprintf("Resource does not exist: %s", $file);
						trigger_error($message, E_USER_WARNING);
					}
				}
				break;
			default:
				$error = sprintf("Invalid language resource type: %s", $type);
				trigger_error($error, E_USER_WARNING);
				break;
		}
	}

	protected function _loadFile($file) {
		if (is_file($file)) {
			$xml = simplexml_load_file($file);

			foreach ($xml->phrase as $phrase) {
				$attributes = $phrase->attributes();

				$key   = strtoupper($attributes['key']);
				$value = (string) $attributes['value'];

				$this->_phrases[$key] = $value;
			}

			return true;
		}

		return false;
	}

	protected function _getPath($code, $locale = null) {
		$path = self::getPath() . DS . $code;

		if (!empty($locale))
			$path .= '-' . $locale;

		return is_dir($path) ? $path : false;
	}

	public function translate($key) {
		return isset($this->_phrases[strtoupper($key)])
		     ? $this->_phrases[strtoupper($key)] : $key;
	}
}
