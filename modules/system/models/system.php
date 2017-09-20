<?php

use Plutonium\Application\Model;
use Plutonium\Database\Table;

class SystemModel extends Model {
    public function getTable() {
		if (is_null($this->_table))
			$this->_table = Table::getInstance($this->name);

		return $this->_table;
	}

    public function validate(&$data) {
        $data['updated'] = gmdate('Y-m-d H:i:s');

        if (!isset($data['created']))
            $data['created'] = $data['updated'];

        return true;
    }
}
