<?php

class Plutonium_Database_Table_Delegate_MySQL
extends Plutonium_Database_Table_Delegate {
	public function select($args) {
		$db = Plutonium_Database_Helper::getAdapter();

		$table = $db->quoteSymbol($this->_table->table_name);

		$where = false;
		$order = false;
		$group = false;

		$single = false;

		if (is_scalar($args) && !empty($args)) {
			$where = $db->quoteSymbol('id') . ' = '
				   . $db->quoteString($args);

			$single = true;
		} elseif (is_array($args) && !empty($args)) {
			$is_assoc = false;

			if (is_assoc($args)) {
				$filters = array();

				foreach ($args as $field => $value) {
					if (is_array($value)) {
						$values = array();

						foreach ($value as $subvalue)
							$values[] = $db->quoteString($subvalue);

						if (!empty($values))
							$filters[] = $db->quoteSymbol($field) . ' IN (' . implode(', ', $values) . ')';
					} else {
						$filters[] = $db->quoteSymbol($field) . ' = '
								   . $db->quoteString($value);
					}
				}

				$where = empty($filters) ? '' : implode(' AND ', $filters);
			} else {
				$values = array();

				foreach ($args as $value) $values[] = $db->quoteString($value);

				$where = $db->quoteSymbol('id') . ' IN (' . implode(', ', $values) . ')';
			}
		}

		$sql = "SELECT * FROM $table"
			 . ($where ? " WHERE $where" : '')
			 . ($group ? " GROUP BY $group" : '')
			 . ($order ? " ORDER BY $order" : '');

		if ($result = $db->query($sql, intval($single))) {
			$rows = array();

			foreach ($result->fetchAllAssoc() as $data)
				$rows[] = $this->_table->make($data);

			$result->close();

			return $single ? $rows[0] : $rows;
		}

		return false;
	}

	public function select_xref($xref, $args) {
		$db = Plutonium_Database_Helper::getAdapter();

		$table = $db->quoteSymbol($this->_table->table_name);
		$id = $db->quoteSymbol('id');

		$xref_table = $db->quoteSymbol($xref->table_name);

		foreach ($xref->table_refs as $key => $table_name) {
			if ($table_name == $this->_table->name) {
				$xref_id = $db->quoteSymbol($key . '_id');
			} else {
				$join_ref_id = $db->quoteSymbol($key . '_id');
				$xref_alias = $key;
			}
		}

		$join = "$table INNER JOIN $xref_table ON "
			  . "$table.$id = $xref_table.$xref_id";

		if (is_scalar($args) && !empty($args)) {
			$ref_id = $db->quoteString($args);
			$where = "$xref_table.$join_ref_id = $ref_id";
		} elseif (is_array($args) && !empty($args)) {
			$filters = array();

			if (array_key_exists('ref_id', $args)) {
				$ref_id = $db->quoteString($args['ref_id']);
				unset($args['ref_id']);

				$filters[] = "$xref_table.$join_ref_id = $ref_id";
			}

			foreach ($args as $field => $value) {
				// TODO handle filtering
			}

			if (!empty($filters))
				$where = implode(' AND ', $filters);
		}

		$fields = array("$table.*");
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
				$field = $db->quoteSymbol($field_meta->name);
				$alias = $db->quoteSymbol('xref_' . $xref_alias . '_' . $field_meta->name);

				$fields[] = "$xref_table.$field AS $alias";

				$xref_fields[] = $db->stripSymbol($alias);
			}
		}

		$fields = implode(', ', $fields);

		$sql = "SELECT $fields FROM $join"
			 . (!empty($where) ? " WHERE $where" : '');

		if ($result = $db->query($sql)) {
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

	public function insert(&$row) {
		$db = Plutonium_Database_Helper::getAdapter();

		$table = $db->quoteSymbol($this->_table->table_name);

		$fields = array();
		$values = array();

		foreach ($this->_table->field_names as $field) {
			if (!is_null($row->$field) && $field != 'id') {
				$fields[] = $db->quoteSymbol($field);
				$values[] = $db->quoteString($row->$field);
			}
		}

		$fields = implode(', ', $fields);
		$values = implode(', ', $values);

		$sql = 'INSERT INTO ' . $table . ' (' . $fields . ') '
			 . 'VALUES (' . $values . ')';

		if ($db->query($sql)) {
			$row->id = $db->getInsertId();

			return true;
		}

		return false;
	}

	public function update(&$row) {
		$db = Plutonium_Database_Helper::getAdapter();

		$table = $db->quoteSymbol($this->_table->table_name);

		$parameters = array();

		foreach ($this->_table->field_names as $field) {
			if (!is_null($row->$field) && $field != 'id') {
				$parameters[] = $db->quoteSymbol($field) . ' = '
							  . $db->quoteString($row->$field);
			}
		}

		$data = implode(', ', $parameters);

		$condition = $db->quoteSymbol('id') . ' = '
				   . $db->quoteString($row->id);

		$sql = 'UPDATE ' . $table . ' '
			 . 'SET ' . $data . ' '
			 . 'WHERE ' . $condition;

		return $db->query($sql);
	}

	public function delete($id) {
		$db = Plutonium_Database_Helper::getAdapter();

		$table = $db->quoteSymbol($this->_table_name);

		$condition = $db->quoteSymbol('id') . ' = '
				   . $db->quoteString($id);

		$sql = 'DELETE FROM ' . $table . ' '
			 . 'WHERE ' . $condition;

		return $db->query($sql);
	}

	public function exists() {
		$db = Plutonium_Database_Helper::getAdapter();

		$sql = "SHOW TABLES LIKE " . $db->quoteString($this->_table->table_name);

		if ($result = $db->query($sql)) {
			$exists = $result->getNumRows() > 0;
			$result->close();

			return $exists;
		}

		return false;
	}

	public function create() {
		$db = Plutonium_Database_Helper::getAdapter();

		$sql = "CREATE TABLE IF NOT EXISTS " . $db->quoteSymbol($this->_table->table_name) . " (\n";

		$lines = array();

		$indexes = array();

		foreach ($this->_table->field_meta as $field_meta) {
			if ($field_meta->type == 'bool') {
				$type = 'TINYINT';
			} elseif ($field_meta->type == 'int') {
				$prefix = array(
					'tiny'  => 'TINY',
					'short' => 'SMALL',
					'long'  => 'BIG'
				);

				$type = @$prefix[$field_meta->size] . 'INT';

				if ($field_meta->unsigned) $type .= ' UNSIGNED';
			} elseif ($field_meta->type == 'float') {
				$type = $field_meta->size == 'long' ? 'DOUBLE' : 'FLOAT';
			} elseif ($field_meta->type == 'string') {
				if (intval($field_meta->length) > 0) {
					$type = 'VARCHAR(' . intval($field_meta->length) . ')';
				} else {
					$prefix = array(
						'tiny'  => 'TINY',
						'short' => 'TINY',
						'long'  => 'LONG'
					);

					$type = @$prefix[$field_meta->size] . 'TEXT';
				}
			} elseif ($field_meta->type == 'date') {
				$type = 'DATETIME';
			} else {
				continue;
			}

			if (!$field_meta->null) $type .= " NOT NULL";

			if ($field_meta->auto) $type .= " AUTO_INCREMENT";

			if ($field_meta->has('default')) {
				$default = $field_meta->null ? 'NULL'
						 : $db->quoteString($field_meta->default);

				$type .= " DEFAULT " . $default;
			}

			$lines[] = $db->quoteSymbol($field_meta->name) . " " . $type;

			if ($field_meta->index || $field_meta->unique) $indexes[] = new Plutonium_Object(array(
				'name'   => $field_meta->name,
				'unique' => $field_meta->unique
			));
		}

		foreach ($indexes as $index) {
			$lines[] = ($index->unique ? "UNIQUE " : "")
					 .  "KEY " . $db->quoteSymbol($index->name)
					 .  " (" . $db->quoteSymbol($index->name) . ")";
		}

		if (array_key_exists('id', $this->_table->field_meta))
			$lines[] = "PRIMARY KEY(" . $db->quoteSymbol('id') . ")";

		if (!empty($lines))
			$sql .= "\t" . implode(",\n\t", $lines) . "\n";

		$sql .=  ");";

		return $db->query($sql);
	}

	public function modify() {
		// Write something here
	}

	public function drop() {
		$db = Plutonium_Database_Helper::getAdapter();

		$sql = "DROP TABLE IF EXISTS " . $db->quoteSymbol($this->_table->table_name);

		return $db->query($sql);
	}
}

?>