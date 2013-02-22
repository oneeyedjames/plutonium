<?php

abstract class Plutonium_Language_Abstract {
	protected $_code;
	protected $_name;
	protected $_phrases;
	
	public function __construct($config) {
		$this->_code    = $config->code;
		$this->_phrases = array();
	}
	
	public function load() {
		$path = P_BASE_PATH . DS . 'languages' . DS . $this->_code;
		$file = $path . DS . 'language.xml';
		
		if (is_file($file)) {
			$xml = simplexml_load_file($file);
			
			foreach ($xml->phrase as $phrase) {
				$attributes = $phrase->attributes();
				$this->_phrases[strtoupper($attributes['key'])] = (string) $attributes['value'];
			}
		}
		
		/*if (is_dir($path)) {
			if (($dir = opendir($path)) !== false) {
				while (($file = readdir($dir)) !== false) {
					$xml = simplexml_load_file($file);
					
					foreach ($xml->phrase as $phrase) {
						$attributes = $phrase->attributes();
						$this->_phrases[strtoupper($attributes['key'])] = $attributes['value'];
					}
				}
			}
		}*/
	}
	
	public function translate($key) {
		return isset($this->_phrases[strtoupper($key)])
		     ? $this->_phrases[strtoupper($key)] : $key;
	}
}

?>