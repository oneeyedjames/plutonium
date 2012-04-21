<?php

abstract class Plutonium_Module_Model_Abstract {
	protected $_name = NULL;
	
	protected $_table = NULL;
	
	public function __construct($name) {
		$this->_name = $name;
	}
	
	public function getTable() {
		if (is_null($this->_table)) {
			$this->_table = Plutonium_Database_Helper::getTable($this->_name);
		}
		
		return $this->_table;
	}
	
	public function find($id = NULL, $args = NULL) {
		return $this->getTable()->find($id, $args);
	}
	
	public function save($data) {
		$row = $this->getTable()->make($data);
		
		return $row->save() ? $row : FALSE;
	}
	
	public function delete($id) {
		$row = $this->getTable()->find($id);
		
		return $row->delete();
	}
}

?>