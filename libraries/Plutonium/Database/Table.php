<?php

class Plutonium_Database_Table {
	protected $_delegate = null;
	
	protected $_name = null;
	
	protected $_table_name = null;
	protected $_table_meta = array();
	protected $_table_xref = array();
	protected $_field_meta = array();
	
	public function __construct($cfg) {
		$type = 'Plutonium_Database_Table_Delegate_' . $cfg->driver;
		
		$this->_delegate = new $type($this);
		
		$this->_name = $cfg->name;
		
		$table_name = array($cfg->prefix);
		
		if ($cfg->prefix == 'mod')
			$table_name[] = Plutonium_Module::getName();
		
		$table_name[] = $cfg->name;
		
		if (isset($cfg->suffix))
			$table_name[] = $cfg->suffix;
		
		$this->_table_name = implode('_', $table_name);
		
		$this->_table_meta = new Plutonium_Object(array(
			'timestamps' => $cfg->timestamps == 'yes'
		));
		
		if ($cfg->suffix != 'xref') {
			$this->_field_meta['id'] = new Plutonium_Object(array(
				'name'     => 'id',
				'type'     => 'int',
				'null'     => false,
				'auto'     => true,
				'unsigned' => true
			));
		}
		
		foreach ($cfg->refs as $ref) {
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
		
		if ($cfg->timestamps == 'yes') {
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
		
		foreach ($cfg->fields as $field) {
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
		
		foreach ($cfg->xrefs as $xref) {
			$this->_table_xref[$xref->name] = new self($xref);
		}
		
		if (!$this->_delegate->exists()) {
			if (!$this->_delegate->create()) {
				die(Plutonium_Database_Helper::getAdapter()->getErrorMsg());
			}
		}
	}
	
	public function __get($key) {
		switch ($key) {
			case 'name':
				return $this->_name;
			case 'table_name':
				return $this->_table_name;
			case 'table_meta':
				return $this->_table_meta;
			case 'table_xref':
				return $this->_table_xref;
			case 'field_names':
				return array_keys($this->_field_meta);
			case 'field_meta':
				return $this->_field_meta;
			default:
				return null;
		}
	}
	
	public function make($data = null) {
		return new Plutonium_Database_Row($this, $this->field_names, $data);
	}
	
	public function find($args = null) {
		return $this->_delegate->select($args);
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