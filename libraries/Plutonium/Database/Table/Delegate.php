<?php

abstract class Plutonium_Database_Table_Delegate {
	protected $_adapter = null;
	protected $_table   = null;

	public function __construct($table) {
		$this->_adapter = Plutonium_Database_Helper::getAdapter();
		$this->_table   = $table;
	}

	public function __get($key) {
		switch ($key) {
			case 'table_name':
				return $this->_adapter->quoteSymbol($this->_table->table_name);
		}
	}

	public function __call($name, $args) {
		if (method_exists($this->_adapter, $name))
			return call_user_func_array(array($this->_adapter, $name), $args);
	}

	abstract public function select($args);
	//abstract public function insert(&$row);
	//abstract public function update(&$row);
	//abstract public function delete($id);

	public function insert(&$row) {
		$fields = array();
		$values = array();

		foreach ($this->_table->field_names as $field) {
			if (!is_null($row->$field) && $field != 'id') {
				$fields[] = $this->quoteSymbol($field);
				$values[] = $this->quoteString($row->$field);
			}
		}

		$fields = implode(', ', $fields);
		$values = implode(', ', $values);

		$sql = "INSERT INTO $this->table_name ($fields) VALUES ($values)";

		if ($this->query($sql)) {
			$row->id = $this->getInsertId();

			return true;
		}

		return false;
	}

	public function update(&$row) {
		$fields = array();

		foreach ($this->_table->field_names as $field) {
			if (!is_null($row->$field) && $field != 'id') {
				$fields[] = $this->quoteSymbol($field) . ' = '
						  . $this->quoteString($row->$field);
			}
		}

		$fields = implode(', ', $fields);

		$where = $this->_whereClause($row->id);

		$sql = "UPDATE $this->table_name SET $fields $where";

		return $this->query($sql);
	}

	public function delete($id) {
		$where = $this->_whereClause($id);

		$sql = "DELETE FROM $this->table_name $where";

		return $this->query($sql);
	}

	protected function _whereClause($id) {
		$field = $this->quoteSymbol('id');
		$value = $this->quoteString($id);

		return "WHERE $field = $value";
	}

	abstract public function exists();
	abstract public function create();
	abstract public function modify();
	//abstract public function drop();

	public function drop() {
		$sql = "DROP TABLE IF EXISTS $this->table_name";

		return $this->query($sql);
	}
}

?>