<?php

class Plutonium_Object_Filter extends Plutonium_Object {
	public function get($key, $default = null, $type = null) {
		$value = parent::get($key, $default);
		
		if (is_string($type)) {
			$object_types = array('bool', 'boolean', 'int', 'integer', 'float', 'double', 'string', 'array', 'object');
			$string_types = array('alpha', 'alnum', 'digit', 'lower', 'upper', 'xdigit');
			
			if (in_array(strtolower($type), $object_types)) {
				$value = Plutonium_Filter_Object::filter($value, $type);
			} elseif (in_array(strtolower($type), $string_types)) {
				$value = Plutonium_Filter_String::filter($value, $type);
			}
		}
		
		return $value;
	}
	
	public function getBool($key, $default = null) {
		return $this->get($key, $default, 'bool');
	}
	
	public function getInt($key, $default = null) {
		return $this->get($key, $default, 'int');
	}
	
	public function getFloat($key, $default = null) {
		return $this->get($key, $default, 'float');
	}
	
	public function getString($key, $default = null) {
		return $this->get($key, $default, 'string');
	}
	
	public function getArray($key, $default = null) {
		return $this->get($key, $default, 'array');
	}
	
	public function getObject($key, $default = null) {
		return $this->get($key, $default, 'object');
	}
	
	public function getAlpha($key, $default = null) {
		return $this->get($key, $default, 'alpha');
	}
	
	public function getAlnum($key, $default = null) {
		return $this->get($key, $default, 'alnum');
	}
	
	public function getDigit($key, $default = null) {
		return $this->get($key, $default, 'digit');
	}
	
	public function getLower($key, $default = null) {
		return $this->get($key, $default, 'lower');
	}
	
	public function getUpper($key, $default = null) {
		return $this->get($key, $default, 'upper');
	}
	
	public function getXDigit($key, $default = null) {
		return $this->get($key, $default, 'xdigit');
	}
}

?>