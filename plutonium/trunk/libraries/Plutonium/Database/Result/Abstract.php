<?php

abstract class Plutonium_Database_Result_Abstract implements Plutonium_Database_Result_Interface {
	protected $_result = null;
	
	public function __construct($result) {
		$this->_result = $result;
	}
	
	public function reset() {
		return $this->seek(0);
	}
	
	public function fetch($type = 'array') {
		switch ($type) {
			case 'array':
				return $this->fetchArray();
			break;
			case 'assoc':
				return $this->fetchAssoc();
			break;
			case 'object':
				return $this->fetchObject();
			break;
			default:
				return false;
			break;
		}
	}
	
	public function fetchAll($type = 'array') {
		if ($this->getNumRows() > 0) $this->reset();
		
		$rows = array();
		
		while ($row = $this->fetch($type)) {
			$rows[] = $row;
		}
		
		return $rows;
	}
	
	public function fetchAllArray() {
		return $this->fetchAll('array');
	}
	
	public function fetchAllAssoc() {
		return $this->fetchAll('assoc');
	}
	
	public function fetchAllObject() {
		return $this->fetchAll('object');
	}
}

?>