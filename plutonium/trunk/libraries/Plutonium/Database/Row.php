<?php

class Plutonium_Database_Row {
	protected $_table = NULL;
	protected $_data  = NULL;
	
	public function __construct(&$table, $fields, $data = null) {
		$this->_table = $table;
		$this->_data  = array();
		
		foreach ($fields as $field) $this->_data[$field] = null;
		
		$this->bind($data);
	}
	
	public function __get($key) {
		return array_key_exists($key, $this->_data)
			 ? $this->_data[$key] : NULL;
	}
	
	public function __set($key, $value) {
		if (array_key_exists($key, $this->_data))
			$this->_data[$key] = $value;
	}
	
	public function bind($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) $this->$key = $value;
		}
	}
	
	public function save() {
		return $this->_table->save($this);
	}
	
	public function validate() {
		return $this->_table->validate($this);
	}
	
	public function delete() {
		return $this->_table->delete($this->id);
	}
	
	public function toArray() {
		return $this->_data;
	}
	
	public function toObject() {
		return (object) $this->_data;
	}
}

?>