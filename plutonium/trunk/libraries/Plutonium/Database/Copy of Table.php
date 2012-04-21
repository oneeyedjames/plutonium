<?php defined('PLUTONIUM_VERSION') or die('Plutonium framework is not initialized.');

class Plutonium_Database_Table {
	const DATATYPE_BOOL  = 'bool';
	const DATATYPE_INT   = 'int';
	const DATATYPE_FLOAT = 'float';
	const DATATYPE_TEXT  = 'text';
	const DATATYPE_BLOB  = 'blob';
	const DATATYPE_DATE  = 'date';
	
	const FINDMODE_LIST = 1;
	const FINDMODE_HASH = 2;
	
	protected $_name = NULL;
	
	protected $_table_name  = NULL;
	protected $_field_names = array();
	
	protected $_dependents = array();
	protected $_references = array();
	
	public function __construct($name, $cfg) {
		$this->_name = $name;
		
		$this->_table_name  = $cfg->table_name;
		$this->_field_names = $cfg->field_names;
		
		$this->_references = $cfg->references;
	}
	
	public function make($data) {
		return new Plutonium_Database_Row($this, $this->_field_names, $this->_references, $data);
	}
	
	public function find($id = NULL, $args = NULL, $mode = self::FINDMODE_LIST) {
		return $this->_select($id, $args, $mode);
	}
	
	public function save(&$row) {
		if ($this->validate($row)) {
			return is_null($row->id)
				 ? $this->_insert($row)
				 : $this->_update($row);
		}
		
		return FALSE;
	}
	
	public function validate(&$row) {
		return TRUE;
	}
	
	public function delete($id) {
		return $this->_delete($id);
	}
	
	protected function _select($id, $args, $mode) {
		$db = Plutonium_Database_Helper::getAdapter();
		
		$table = $db->quoteSymbol($this->_table_name);
		
		$where = FALSE;
		$order = FALSE;
		$group = FALSE;
		
		$single = FALSE;
		
		if (is_scalar($id) && !empty($id)) {
			$where = $db->quoteSymbol('id') . ' = '
				   . $db->quoteString($id);
			
			$single = TRUE;
		} elseif (is_array($id) && !empty($id)) {
			if ($mode == self::FINDMODE_LIST) {
				$values = array();
				
				foreach ($id as $value) $values[] = $db->quoteString($value);
				
				$where = $db->quoteSymbol('id') . ' IN (' . implode(', ', $values) . ')';
			} elseif ($mode == self::FINDMODE_HASH) {
				$filters = array();
				
				foreach ($id as $field => $value) {
					$filters[] = $db->quoteSymbol($field) . ' = '
							   . $db->quoteString($value);
				}
				
				$where = empty($filters) ? '' : implode(' AND ', $filters);
			}
		}
		/*elseif (is_array($args) && empty($id)) {
			if (array_key_exists('filters', $args)) {
				$where  = $args['filters'][0];
				$params = $args['filters'][1];
				
				$regex_field = '/:([A-Za-z0-9_]+)\b/';
				$regex_param = '/@([A-Za-z0-9_]+)\b/';
				
				$matches = array();
				
				if (preg_match_all($regex_field, $where, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$pattern = '/:' . $match[1] . '\b/';
						$replace = $db->quoteSymbol($match[1]);
						
						$where = preg_replace($pattern, $replace, $where, 1);
					}
				}
				
				$matches = array();
				
				if (preg_match_all($regex_param, $where, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$pattern = '/@' . $match[1] . '\b/';
						$replace = $db->quoteString($params[$match[1]]);
						
						$where = preg_replace($pattern, $replace, $where, 1);
					}
				}
			}
			
			if (array_key_exists('group', $args)) {
				$group = $args['group'];
				$regex = '/:([A-Za-z0-9_]+)\b/';
				
				$matches = array();
				
				if (preg_match_all($regex, $group, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$pattern = '/:' . $match[1] . '\b/';
						$replace = $db->quoteSymbol($match[1]);
						
						$group = preg_replace($pattern, $replace, $group, 1);
					}
				}
			}
			
			if (array_key_exists('order', $args)) {
				$order = $args['order'];
				$regex = '/:([A-Za-z0-9_]+)\b/';
				
				$matches = array();
				
				if (preg_match_all($regex, $order, $matches, PREG_SET_ORDER)) {
					foreach ($matches as $match) {
						$pattern = '/:' . $match[1] . '\b/';
						$replace = $db->quoteSymbol($match[1]);
						
						$order = preg_replace($pattern, $replace, $order, 1);
					}
				}
			}
		}*/

		$sql = 'SELECT * '
			 . 'FROM ' . $table . ' '
			 . ($where ? 'WHERE ' . $where : '') . ' '
			 . ($group ? 'GROUP BY ' . $group : '') . ' '
			 . ($order ? 'ORDER BY ' . $order : '') . ' ';
		
		if ($result = $db->query($sql, 1)) {
			$rows = array();
			
			foreach ($result->fetchAllAssoc() as $data) {
				$rows[] = $this->make($data);
			}
			
			foreach ($this->_references as $name => $reference) {
				$table_name   = $reference['table'];
				$local_key    = $reference['localkey'];
				$foreign_key  = $reference['foreignkey'];
				
				foreach ($rows as $row) {
					$keys[$local_key][] = $row->$local_key;
				}
				
				$table = Plutonium_Database_Helper::getTable($table_name);
				
				$ref_rows = $table->find($keys[$local_key]);
				
				foreach ($rows as &$row) {
					foreach ($ref_rows as $ref_row) {
						if ($row->$local_key == $ref_row->$foreign_key) {
							$row->$name = $ref_row;
						}
					}
				}
			}
			
			return $single ? $rows[0] : $rows;
		}
		
		return FALSE;
	}
	
	protected function _insert(&$row) {
		$db = Plutonium_Database_Helper::getAdapter();
		
		$table = $db->quoteSymbol($this->_table_name);
		
		$fields = array();
		$values = array();
		
		foreach ($this->_field_names as $field) {
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
			
			return TRUE;
		}
		
		return FALSE;
	}
	
	protected function _update(&$row) {
		$db = Plutonium_Database_Helper::getAdapter();
		
		$table = $db->quoteSymbol($this->_table_name);
		
		$parameters = array();
		
		foreach ($this->_field_names as $field) {
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
	
	protected function _delete($id) {
		$db = Plutonium_Database_Helper::getAdapter();
		
		$table = $db->quoteSymbol($this->_table_name);
		
		$condition = $db->quoteSymbol('id') . ' = '
				   . $db->quoteString($id);
		
		$sql = 'DELETE FROM ' . $table . ' '
			 . 'WHERE ' . $condition;
		
		return $db->query($sql);
	}
	
	protected function _create() {
		$sql = "CREATE TABLE `mod_" . Plutonium_Module_Helper::getName(). "_" . $cfg->name . "` (\n"
			 . "\t`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,\n";
		
		foreach ($cfg->fields as $field) {
			if ($field->type == 'bool') {
				$type = 'TINYINT';
			} elseif ($field->type == 'int') {
				$prefix = array(
					'tiny'  => 'TINY',
					'short' => 'SMALL',
					'long'  => 'BIG'
				);
				
				$type = @$prefix[$field->size] . 'INT';
			} elseif ($field->type == 'float') {
				$type = $field->size == 'long' ? 'DOUBLE' : 'FLOAT';
			} elseif ($field->type == 'string') {
				$prefix = array(
					'short' => 'TINY',
					'long'  => 'LONG'
				);
				
				$type = @$prefix[$field->size] . 'TEXT';
			} else {
				continue;
			}
			
			$sql .= "\t`" . $field->name . "` " . $type . ",\n";
		}
		
		$sql .= "\tPRIMARY KEY(`id`)\n"
			 .  ");";
					
		die('<pre>' . $sql . '</pre>');
	}
	
	protected function _modify() {
	}
	
	protected function _drop() {
	}
}
