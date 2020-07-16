<?php

use Plutonium\Application\Model;
use Plutonium\Database\Table;

class ComponentsModel extends Model {
	public function getTable() {
		if (is_null($this->_table))
			$this->_table = Table::getInstance($this->name);

		return $this->_table;
	}
}
