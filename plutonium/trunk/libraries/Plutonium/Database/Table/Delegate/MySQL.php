<?php

class Plutonium_Database_Table_Delegate_MySQL
extends Plutonium_Database_Table_Delegate {
	public function exists() {
		$sql = "SHOW TABLES LIKE $this->table_name";

		if ($result = $this->query($sql)) {
			$exists = $result->getNumRows() > 0;
			$result->close();

			return $exists;
		}

		return false;
	}

	public function create() {
		$sql = "CREATE TABLE IF NOT EXISTS $this->table_name (\n";

		$lines = array();

		$indexes = array();

		foreach ($this->_table->field_meta as $field_meta) {
			$field = $this->quoteSymbol($field_meta->name);
			$type = $this->_createFieldType($field_meta);

			if (!$type)
				continue;

			if (!$field_meta->null)
				$type .= " NOT NULL";

			if ($field_meta->auto)
				$type .= " AUTO_INCREMENT";

			if ($field_meta->has('default')) {
				$default = $this->quoteString($field_meta->default);

				$type .= " DEFAULT $default";
			}

			$lines[] = "$field $type";

			if ($field_meta->index || $field_meta->unique) {
				$indexes[] = new Plutonium_Object(array(
					'name'   => $field_meta->name,
					'unique' => $field_meta->unique
				));
			}
		}

		foreach ($indexes as $index) {
			$lines[] = ($index->unique ? "UNIQUE " : "")
					 .  "KEY " . $this->quoteSymbol($index->name)
					 .  " (" . $this->quoteSymbol($index->name) . ")";
		}

		if (array_key_exists('id', $this->_table->field_meta))
			$lines[] = "PRIMARY KEY (" . $this->quoteSymbol('id') . ")";

		if (!empty($lines))
			$sql .= "\t" . implode(",\n\t", $lines) . "\n";

		$sql .=  ")";

		return $this->query($sql);
	}

	protected function _createFieldType($field_meta) {
		switch ($field_meta->type) {
			case 'bool':
				return 'TINYINT';
			case 'int':
				$prefix = array('short' => 'SMALL', 'long'  => 'BIG');
				$type = @$prefix[$field_meta->size] . 'INT';

				if ($field_meta->unsigned)
					$type .= ' UNSIGNED';

				return $type;
			case 'float':
				return $field_meta->size == 'long' ? 'DOUBLE' : 'FLOAT';
			case 'string':
				if (intval($field_meta->length) > 0) {
					$length = intval($field_meta->length);
					return "VARCHAR($length)";
				} else {
					$prefix = array('short' => 'TINY', 'long'  => 'LONG');
					return @$prefix[$field_meta->size] . 'TEXT';
				}
				break;
			case 'date':
				return 'DATETIME';
			default:
				return false;
		}
	}

	public function modify() {
		// TODO method stub
	}
}

?>