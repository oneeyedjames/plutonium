<?php

class Plutonium_Language {
	protected static $_path = null;
	
	protected static $_instance = null;
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/languages');
		
		return self::$_path;
	}
	
	public static function &getInstance($config = null) {
		if (is_null(self::$_instance) && !is_null($config))
			self::$_instance = new self($config);
		
		return self::$_instance;
	}

	protected $_name;
	protected $_code;
	protected $_locale;
	protected $_phrases;
	
	public function __construct($config) {
		$code   = $config->code;
		$locale = null;
		
		if (strpos($code, '-') !== false)
			list($code, $locale) = explode('-', $code);
		
		$this->_code   = $config->code   = strtolower($code);
		$this->_locale = $config->locale = strtoupper($locale);
		
		$this->_phrases = array();
		
		$path = self::getPath() . DS . $this->_code . '-' . $this->_locale;
		
		if (!is_dir($path)) {
			$message = sprintf("Could not find language pack, %s-%s.",
					$config->code, $config->locale);
			
			$path = self::getPath() . DS . $this->_code;
			
			if (is_dir($path)) {
				$this->_locale = null;
				
				$error = sprintf("%s Instead, using %s.", $message, $this->_code);
				
				trigger_error($error, E_USER_WARNING);
			} elseif ($dir = @opendir(self::getPath())) {
				while (($file = readdir($dir)) !== false) {
					if (is_dir(self::getPath() . DS . $file) && strpos($file, '-') !== false) {
						list($code, $locale) = explode('-', $file);
						
						if ($this->_code == strtolower($code)) {
							$this->_locale = strtoupper($locale);
							
							$error = sprintf("%s Instead, using %s-%s.",
								$message, $this->_code, $this->_locale);
							
							trigger_error($error, E_USER_WARNING);
							
							break;
						}
					}
				}
				
				closedir($dir);
			} else {
				trigger_error($message, E_USER_WARNING);
			}
		}
		
		$file = self::getPath() . DS . $this->_code . DS . 'language.xml';
		
		$this->_loadFile($file);
	}
	
	public function load($name, $type) {
		$name = strtolower($name);
		$type = strtolower($type);
		
		switch ($type) {
			case 'themes':
			case 'modules':
			case 'widgets':
				$path = self::getPath() . DS . $this->_code . DS . $type;
				$file = $path . DS . $name . '.xml';
				$this->_loadFile($file);
				break;
			default:
				// TODO raise warning
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
		} else {
			// TODO raise warning
		}
	}
	
	public function translate($key) {
		return isset($this->_phrases[strtoupper($key)])
		     ? $this->_phrases[strtoupper($key)] : $key;
	}
}

?>