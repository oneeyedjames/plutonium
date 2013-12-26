<?php

abstract class Plutonium_Database_Table_Delegate {
	protected $_table = null;

	public function __construct($table) {
		$this->_table = $table;
	}

	abstract public function select($args);
	abstract public function insert(&$row);
	abstract public function update(&$row);
	abstract public function delete($id);

	abstract public function exists();
	abstract public function create();
	abstract public function modify();
	abstract public function drop();
}

?>