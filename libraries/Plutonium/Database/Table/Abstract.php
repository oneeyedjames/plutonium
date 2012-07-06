<?php defined('PLUTONIUM_VERSION') or die('Plutonium framework is not initialized.');

abstract class Plutonium_Database_Table_Abstract {
	protected $_delegate = NULL;
	
	protected $_name = NULL;
	
	protected $_table_name  = NULL;
	protected $_table_meta  = array();
	protected $_field_names = array();
	protected $_field_meta  = array();
	
	public function __construct($cfg) {
		$this->_delegate = Plutonium_Database_Helper::getTableDelegate($cfg->driver, $this);
		
		$this->_name = $cfg->name;
		
		$table_name = array($cfg->prefix);
		
		if ($cfg->prefix == 'mod') {
			$table_name[] = Plutonium_Module_Helper::getName();
		}
		
		$table_name[] = $cfg->name;
		
		$this->_table_name = implode('_', $table_name);
		
		$this->_table_meta = new Plutonium_Object(array(
			'timestamps' => $cfg->timestamps == 'yes'
		));
		
		$this->_field_names[] = 'id';
		
		$this->_field_meta['id'] = new Plutonium_Object(array(
			'name'     => 'id',
			'type'     => 'int',
			'null'     => FALSE,
			'auto'     => TRUE,
			'unsigned' => TRUE
		));
		
		foreach ($cfg->refs as $ref) {
			$field_name = $ref->name . '_id';
			
			$this->_field_names[] = $field_name;
			
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
			$this->_field_names[] = 'created';
			$this->_field_names[] = 'updated';
			
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
			$this->_field_names[] = $field->name;
			
			$this->_field_meta[$field->name] = new Plutonium_Object(array(
				'name'     => $field->name,
				'type'     => $field->type,
				'size'     => $field->size,
				'null'     => $field->null != 'no',
				'unsigned' => $field->unsigned == 'yes',
				'default'  => $field->default
			));
		}
		
		$this->_delegate->create();
	}
	
	public function __get($key) {
		switch ($key) {
			case 'name':
				return $this->_name;
			break;
			case 'table_name':
				return $this->_table_name;
			break;
			case 'table_meta':
				return $this->_table_meta;
			break;
			case 'field_names':
				return $this->_field_names;
			break;
			case 'field_meta':
				return $this->_field_meta;
			break;
			default:
				return NULL;
			break;
		}
	}
	
	public function make($data) {
		return new Plutonium_Database_Row($this, $this->_field_names, $data);
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