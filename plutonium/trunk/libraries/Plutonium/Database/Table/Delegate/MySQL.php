<?php

class Plutonium_Database_Table_Delegate_MySQL
extends Plutonium_Database_Table_Delegate {
	public function select($args) {
		$single = false;

		$sql = "SELECT * FROM $this->table_name";

		if (is_scalar($args) && !empty($args)) {
			$where = $this->quoteSymbol('id') . ' = '
				   . $this->quoteString($args);

			$single = true;
		} elseif (is_array($args) && !empty($args)) {
			if (is_assoc($args)) {
				$filters = array();

				foreach ($args as $field => $value) {
					if (is_array($value)) {
						$values = array();

						foreach ($value as $subvalue)
							$values[] = $this->quoteString($subvalue);

						if (!empty($values))
							$filters[] = $this->quoteSymbol($field) . ' IN '
								       . '(' . implode(', ', $values) . ')';
					} else {
						$filters[] = $this->quoteSymbol($field) . ' = '
								   . $this->quoteString($value);
					}
				}

				if (!empty($filters))
					$where = implode(' AND ', $filters);
			} else {
				$values = array();

				foreach ($args as $value)
					$values[] = $this->quoteString($value);

				$where = $this->quoteSymbol('id') . ' IN '
					   . '(' . implode(', ', $values) . ')';
			}
		}

		if (!empty($where))
			$sql .= " WHERE $where";

		/* TODO implement grouping
		if (!empty($group))
			$sql .= " GROUP BY $group"; */

		/* TODO implement ordering
		if (!empty($order))
			$sql .= " ORDER BY $order"; */

		if ($result = $this->query($sql, intval($single))) {
			$rows = array();

			foreach ($result->fetchAllAssoc() as $data)
				$rows[] = $this->_table->make($data);

			$result->close();

			return $single ? $rows[0] : $rows;
		}

		return false;
	}

	public function select_xref($xref, $args) {
		$table = $this->table_name;
		$id = $this->quoteSymbol('id');

		$xref_table = $this->quoteSymbol($xref->table_name);

		foreach ($xref->table_refs as $key => $table_name) {
			if ($table_name == $this->_table->name) {
				$xref_id = $this->quoteSymbol($key . '_id');
			} else {
				$join_ref_id = $this->quoteSymbol($key . '_id');
				$xref_alias = $key;
			}
		}

		$join = "$this->table_name INNER JOIN $xref_table ON "
			  . "$this->table_name.$id = $xref_table.$xref_id";

		if (is_scalar($args) && !empty($args)) {
			$ref_id = $this->quoteString($args);
			$where = "$xref_table.$join_ref_id = $ref_id";
		} elseif (is_array($args) && !empty($args)) {
			$filters = array();

			if (array_key_exists('ref_id', $args)) {
				$ref_id = $this->quoteString($args['ref_id']);
				unset($args['ref_id']);

				$filters[] = "$xref_table.$join_ref_id = $ref_id";
			}

			foreach ($args as $field => $value) {
				// TODO handle filtering
			}

			if (!empty($filters))
				$where = implode(' AND ', $filters);
		}

		$fields = array("$this->table_name.*");
		$xref_fields = array();

		foreach ($xref->field_meta as $field_meta) {
			$is_ref = false;

			foreach ($xref->table_refs as $ref_alias => $ref_table) {
				if ($ref_alias . '_id' == $field_meta->name) {
					$is_ref = true;
					break;
				}
			}

			if (!$is_ref) {
				$field = $this->quoteSymbol($field_meta->name);
				$alias = $this->quoteSymbol('xref_' . $xref_alias . '_' . $field_meta->name);

				$fields[] = "$xref_table.$field AS $alias";

				$xref_fields[] = $this->stripSymbol($alias);
			}
		}

		$fields = implode(', ', $fields);

		$sql = "SELECT $fields FROM $join";

		if (!empty($where))
			$sql .= " WHERE $where";

		if ($result = $this->query($sql)) {
			$rows = array();

			foreach ($result->fetchAllAssoc() as $data) {
				$xref_data = array();

				foreach ($xref_fields as $xref_field) {
					$field = str_replace('xref_' . $xref_alias . '_', '', $xref_field);
					$xref_data[$xref_alias][$field] = $data[$xref_field];
				}

				$rows[] = $this->_table->make($data, $xref_data);
			}

			$result->close();

			return $rows;
		}

		return false;
	}

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