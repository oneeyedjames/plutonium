<?php

class Plutonium_Module_Model {
	protected $_name   = null;
	protected $_table  = null;
	protected $_module = null;

	public function __construct($args) {
		$this->_name   = $args->name;
		$this->_module = $args->module;
	}

	public function __get($key) {
		switch ($key) {
			case 'name':
				return $this->_name;
		}
	}

	public function getTable() {
		if (is_null($this->_table))
			$this->_table = Plutonium_Database_Helper::getTable($this->_name, $this->_module->name);

		return $this->_table;
	}

	public function find($args = null) {
		return $this->getTable()->find($args);
	}

	public function save($data) {
		$row = $this->getTable()->make($data);

		return $row->save() ? $row : false;
	}

	public function delete($id) {
		$row = $this->getTable()->find($id);

		return $row->delete();
	}
}