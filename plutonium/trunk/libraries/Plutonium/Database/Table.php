<?php

class Plutonium_Database_Table {
	protected $_delegate = null;
	
	protected $_name   = null;
	protected $_prefix = null;
	protected $_suffix = null;
	protected $_module = null;
	
	protected $_table_name = null;
	protected $_table_meta = array();
	protected $_field_meta = array();
	
	protected $_table_refs  = array();
	protected $_table_revs  = array();
	protected $_table_xrefs = array();
	
	public function __construct($config) {
		$type = 'Plutonium_Database_Table_Delegate_' . $config->driver;
		
		$this->_delegate = new $type($this);
		
		$this->_name   = $config->name;
		$this->_prefix = $config->prefix;
		$this->_suffix = $config->suffix;
		
		$table_name = array($config->prefix);
		
		if ($config->prefix == 'mod')
			$table_name[] = $this->_module = Plutonium_Module::getName();
		
		$table_name[] = $config->name;
		
		if (isset($config->suffix))
			$table_name[] = $config->suffix;
		
		$this->_table_name = implode('_', $table_name);
		
		$this->_table_meta = new Plutonium_Object(array(
			'timestamps' => $config->timestamps == 'yes'
		));
		
		if ($config->suffix != 'xref') {
			$this->_field_meta['id'] = new Plutonium_Object(array(
				'name'     => 'id',
				'type'     => 'int',
				'null'     => false,
				'auto'     => true,
				'unsigned' => true
			));
		}
		
		foreach ($config->refs as $ref) {
			$this->_table_refs[$ref->name] = $ref->table;
			
			$field_name = $ref->name . '_id';
			
			$this->_field_meta[$field_name] = new Plutonium_Object(array(
				'name'     => $field_name,
				'type'     => 'int',
				'null'     => false,
				'unsigned' => true,
				'default'  => 0,
				'index'    => true
			));
		}
		
		if ($config->timestamps == 'yes') {
			$this->_field_meta['created'] = new Plutonium_Object(array(
				'name'    => 'created',
				'type'    => 'date',
				'null'    => true,
				'default' => false
			));
			
			$this->_field_meta['updated'] = new Plutonium_Object(array(
				'name'    => 'updated',
				'type'    => 'date',
				'null'    => true,
				'default' => false
			));
		}
		
		foreach ($config->fields as $field) {
			$this->_field_meta[$field->name] = new Plutonium_Object(array(
				'name'     => $field->name,
				'type'     => $field->type,
				'size'     => $field->size,
				'length'   => $field->length,
				'null'     => $field->null != 'no',
				'unsigned' => $field->unsigned == 'yes',
				'default'  => $field->default
			));
		}
		
		// TODO raise error
		if (!$this->_delegate->exists() && !$this->_delegate->create())
			die(Plutonium_Database_Helper::getAdapter()->getErrorMsg());
	}
	
	public function __get($key) {
		switch ($key) {
			case 'name':
				return $this->_name;
			case 'table_name':
				return $this->_table_name;
			case 'table_meta':
				return $this->_table_meta;
			case 'field_names':
				return array_keys($this->_field_meta);
			case 'field_meta':
				return $this->_field_meta;
			case 'table_refs':
				return $this->_table_refs;
			case 'table_revs':
				if (empty($this->_table_revs)) {
					$this->_table_revs = Plutonium_Database_Helper::getRefs($this->_name);
					
					foreach ($this->_table_revs as &$rev) {
						$table = Plutonium_Database_Helper::getTable($rev->table);
						
						if ($table->_prefix == 'mod')
							$rev->alias = $table->_module . '_' . $rev->alias;
					}
				}
				
				return $this->_table_revs;
			case 'table_xrefs':
				if (empty($this->_table_xrefs))
					$this->_table_xrefs = Plutonium_Database_Helper::getXRefs($this->_name);
				
				return $this->_table_xrefs;
			default:
				return null;
		}
	}
	
	public function make($data = null, $xref_data = null) {
		return new Plutonium_Database_Row($this, $data, $xref_data);
	}
	
	public function find($args = null) {
		return $this->_delegate->select($args);
	}
	
	public function find_xref($xref, $args = null) {
		return $this->_delegate->select_xref($this->table_xrefs[$xref], $args);
	}
	
	public function save(&$row) {
		if ($this->validate($row)) {
			return is_null($row->id)
				 ? $this->_delegate->insert($row)
				 : $this->_delegate->update($row);
		}
		
		return false;
	}
	
	public function delete($id) {
		return $this->_delegate->delete($id);
	}
	
	public function validate(&$row) {
		return true;
	}
}

?>