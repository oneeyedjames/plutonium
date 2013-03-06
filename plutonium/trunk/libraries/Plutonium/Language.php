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
	
	protected $_code;
	protected $_name;
	protected $_phrases;
	
	public function __construct($config) {
		$this->_code    = strtolower($config->code);
		$this->_phrases = array();
		
		$path = self::getPath() . DS . $this->_code;
		
		if (!is_dir($path)) {
			$code = explode('-', $config->code);
			$path = self::getPath() . DS . $code[0];
			
			if (is_dir($path)) {
				$this->_code = $code[0];
				
				// TODO raise warning
			} elseif ($dir = @opendir(self::getPath())) {
				while (($file = readdir($dir)) !== false) {
					if (is_dir(self::getPath() . DS . $file)) {
						$code2 = explode('-', $file);
						
						if ($code[0] == $code2[0]) {
							$this->_code = $file;
				
							// TODO raise warning
							
							break;
						}
					}
				}
			}
		}
	}
	
	public function load($module = null) {
		$file = self::getPath() . DS . $this->_code . DS . 'language.xml';
		
		if (is_file($file)) {
			
			$xml = simplexml_load_file($file);
			
			foreach ($xml->phrase as $phrase) {
				$attributes = $phrase->attributes();
				$this->_phrases[strtoupper($attributes['key'])] = (string) $attributes['value'];
			}
		} else {
			// TODO raise warning
		}
		
		if (!empty($module)) {
			$path = self::getPath() . DS . $this->_code . DS . 'modules';
			$file = $path . DS . strtolower($module) . '.xml';
			
			if (is_file($file)) {
				$xml = simplexml_load_file($file);
			
				foreach ($xml->phrase as $phrase) {
					$attributes = $phrase->attributes();
					$this->_phrases[strtoupper($attributes['key'])] = (string) $attributes['value'];
				}
			} else {
				// TODO raise warning
			}
		}
	}
	
	public function translate($key) {
		return isset($this->_phrases[strtoupper($key)])
		     ? $this->_phrases[strtoupper($key)] : $key;
	}
}

?>