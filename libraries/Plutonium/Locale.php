<?php

class Plutonium_Locale {
	protected static $_path = null;

	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/locales');

		return self::$_path;
	}

	protected $_language;
	protected $_country;
	protected $_phrases;

	public function __construct($config) {
		if (is_string($config))
			$config = $this->_parseString($config);
		elseif (is_array($config))
			$config = new Plutonium_Object($config);

		if (is_a($config, 'Plutonium_Object')) {
			$this->_language = strtolower($config->language);
			$this->_country  = strtoupper($config->country);
		}

		$this->_phrases = array();

		if ($path = $this->_getPath($this->_language)) {
			$this->_loadFile($path . DS . 'language.xml');
		} else {
			$message = sprintf("Could not find language resource: %s.",
				$this->_language
			);

			trigger_error($message, E_USER_WARNING);
		}

		if (!empty($this->_country)) {
			if ($path = $this->_getPath($this->_language, $this->_country)) {
				$this->_loadFile($path . DS . 'language.xml');
			} else {
				$message = sprintf("Could not find language resource: %s-%s.",
					$this->_language,
					$this->_country
				);

				trigger_error($message, E_USER_NOTICE);
			}
		}
	}

	public function __get($key) {
		switch ($key) {
			case 'name':
				$name = $this->_language;

				if (!empty($this->_country))
					$name .= '-' . $this->_country;

				return $name;
			case 'language':
				return $this->_language;
			case 'country':
				return $this->_country;
		}
	}

	public function load($name, $type) {
		$name = strtolower($name);
		$type = strtolower($type);

		switch ($type) {
			case 'themes':
			case 'modules':
			case 'widgets':
				$path = self::getPath() . DS . $this->language . DS . $type;
				$file = $path . DS . $name . '.xml';

				if (!$this->_loadFile($file)) {
					$message = sprintf("Resource does not exist: %s", $file);
					trigger_error($message, E_USER_WARNING);
				}

				if (!empty($this->country)) {
					$path = self::getPath() . DS . $this->name . DS . $type;
					$file = $path . DS . $name . '.xml';

					if (!$this->_loadFile($file)) {
						$message = sprintf("Resource does not exist: %s", $file);
						trigger_error($message, E_USER_WARNING);
					}
				}
				break;
			default:
				$error = sprintf("Invalid locale resource type: %s", $type);
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

	protected function _getPath($language, $country = null) {
		$path = self::getPath() . DS . $language;

		if (!empty($country))
			$path .= '-' . $country;

		return is_dir($path) ? $path : false;
	}

	protected function _parseString($locale) {
		if (strpos($locale, '-') !== false) {
			list($language, $country) = explode('-', $locale, 2);
			$locale = array('language' => $language, 'country' => $country);
		} else {
			$locale = array('language' => $locale);
		}

		return new Plutonium_Object($locale);
	}

	public function localize($key) {
		if (!isset($this->_phrases[strtoupper($key)])) return $key;

		$match = $this->_phrases[strtoupper($key)];

		if (func_num_args() == 1) return $match;

		$args = func_get_args();
		$args[0] = $match;

		return call_user_func_array('sprintf', $args);
	}
}
