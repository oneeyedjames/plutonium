<?php

class Plutonium_Object
implements Plutonium_Accessible, ArrayAccess, Iterator, Countable {
	protected $_vars;
	
	// Constructor
	public function __construct($data = null) {
		$this->_vars = array();
		
		if (is_a($data, 'Plutonium_Object')) {
			$this->_vars = $data->_vars;
		} elseif (is_array($data)) {
			foreach ($data as $key => $value) {
				if (is_scalar($value) || is_resource($value)) {
					$this->set($key, $value);
				} elseif (is_array($value) || is_object($value)) {
					$this->set($key, new Plutonium_Object($value));
				}
			}
		}
	}
	
	// Magic methods
	public function __get($key) {
		return $this->get($key);
	}
	
	public function __set($key, $value) {
		$this->set($key, $value);
	}
	
	public function __isset($key) {
		return $this->has($key);
	}
	
	public function __unset($key) {
		$this->del($key);
	}
	
	// Plutonium_Accessible methods
	public function has($key) {
		return array_key_exists($key, $this->_vars);
	}
	
	public function get($key, $default = null) {
		return $this->has($key) ? $this->_vars[$key] : $default;
	}
	
	public function set($key, $value = null) {
		$this->_vars[$key] = $value;
	}
	
	public function def($key, $value = null) {
		if (!$this->has($key)) $this->_vars[$key] = $value;
	}
	
	public function del($key) {
		if ($this->has($key)) unset($this->_vars[$key]);
	}
	
	// ArrayAccess methods
	public function offsetGet($key) {
		return $this->get($key);
	}
	
	public function offsetSet($key, $value) {
		$this->set($key, $value);
	}
	
	public function offsetExists($key) {
		return $this->has($key);
	}
	
	public function offsetUnset($key) {
		$this->del($key);
	}
	
	// Iterator methods
	public function current() {
		return current($this->_vars);
	}
	
	public function key() {
		return key($this->_vars);
	}
	
	public function next() {
		return next($this->_vars);
	}
	
	public function rewind() {
		return reset($this->_vars);
	}
	
	public function valid() {
		return key($this->_vars) !== null;
	}
	
	// Countable method
	public function count() {
		return count($this->_vars);
	}
	
	public function toArray() {
		$vars = array();
		
		foreach ($this->_vars as $key => $value) {
			$vars[$key] = is_a($value, __CLASS__) ? $value->toArray() : $value;
		}
		
		return $vars;
	}
}

interface Plutonium_Accessible {
	public function has($key);
	public function get($key, $default = null);
	public function set($key, $value = null);
	public function def($key, $value = null);
	public function del($key);
}

?>