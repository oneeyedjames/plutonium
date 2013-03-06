<?php

class Plutonium_Language {
	protected static $_instance = null;
	
	public static function &getInstance($config = null) {
		if (is_null(self::$_instance) && !is_null($config))
			self::$_instance = new self($config);
		
		return self::$_instance;
	}
	
	protected $_code;
	protected $_name;
	protected $_phrases;
	
	public function __construct($config) {
		$this->_code    = $config->code;
		$this->_phrases = array();
	}
	
	public function load($module = null) {
		$path = PU_PATH_BASE . DS . 'languages' . DS . $this->_code;
		$file = $path . DS . 'language.xml';
		
		if (is_file($file)) {
			$xml = simplexml_load_file($file);
			
			foreach ($xml->phrase as $phrase) {
				$attributes = $phrase->attributes();
				$this->_phrases[strtoupper($attributes['key'])] = (string) $attributes['value'];
			}
		}
		
		if (!empty($module)) {
			$path .= DS . 'modules';
			$file =  $path . DS . strtolower($module) . '.xml';
			//die($file);
			if (is_file($file)) {
				$xml = simplexml_load_file($file);
			
				foreach ($xml->phrase as $phrase) {
					$attributes = $phrase->attributes();
					$this->_phrases[strtoupper($attributes['key'])] = (string) $attributes['value'];
				}
			}
		}
	}
	
	public function translate($key) {
		return isset($this->_phrases[strtoupper($key)])
		     ? $this->_phrases[strtoupper($key)] : $key;
	}
}

?>