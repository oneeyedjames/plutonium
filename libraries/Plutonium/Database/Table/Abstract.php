<?php defined('PLUTONIUM_VERSION') or die('Plutonium framework is not initialized.');

abstract class Plutonium_Database_Table_Abstract
implements Plutonium_Database_Table_Interface {
	protected $_delegate = NULL;
	
	protected $_name = NULL;
	
	protected $_table_name = NULL;
	protected $_table_meta = array();
	protected $_table_xref = array();
	protected $_field_meta = array();
	
	public function __construct($cfg) {
		$type = 'Plutonium_Database_Table_Delegate_' . $cfg->driver;
		
		$this->_delegate = new $type($this);
		
		$this->_name = $cfg->name;
		
		$table_name = array($cfg->prefix);
		
		if ($cfg->prefix == 'mod')
			$table_name[] = Plutonium_Module_Helper::getName();
		
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
				'null'     => FALSE,
				'auto'     => TRUE,
				'unsigned' => TRUE
			));
		}
		
		foreach ($cfg->refs as $ref) {
			$field_name = $ref->name . '_id';
			
			$this->_field_meta[$field_name] = new Plutonium_Object(array(
				'name'     => $field_name,
				'type'     => 'int',
				'null'     => FALSE,
				'unsigned' => TRUE,
				'default'  => 0,
				'index'    => TRUE
			));
		}
		
		if ($cfg->timestamps == 'yes') {
			$this->_field_meta['created'] = new Plutonium_Object(array(
				'name'    => 'created',
				'type'    => 'date',
				'null'    => TRUE,
				'default' => FALSE
			));
			
			$this->_field_meta['updated'] = new Plutonium_Object(array(
				'name'    => 'updated',
				'type'    => 'date',
				'null'    => TRUE,
				'default' => FALSE
			));
		}
		
		foreach ($cfg->fields as $field) {
			$this->_field_meta[$field->name] = new Plutonium_Object(array(
				'name'     => $field->name,
				'type'     => $field->type,
				'size'     => $field->size,
				'null'     => $field->null != 'no',
				'unsigned' => $field->unsigned == 'yes',
				'default'  => $field->default
			));
		}
		
		foreach ($cfg->xrefs as $xref) {
			$this->_table_xref[$xref->name] = new Plutonium_Database_Table_Default($xref);
		}
		
		$this->_delegate->create();
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
				return NULL;
		}
	}
	
	public function make($data = NULL) {
		return new Plutonium_Database_Row($this, $this->field_names, $data);
	}
	
	public function find($args = NULL) {
		return $this->_delegate->select($args);
	}
	
	public function save(&$row) {
		if ($this->validate($row)) {
			return is_null($row->id)
				 ? $this->_delegate->insert($row)
				 : $this->_delegate->update($row);
		}
		
		return FALSE;
	}
	
	public function delete($id) {
		return $this->_delegate->delete($id);
	}
	
	public function validate(&$row) {
		return TRUE;
	}
}

?>