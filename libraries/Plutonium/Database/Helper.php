<?php

class Plutonium_Database_Helper {
	protected static $_adapter = null;
	
	public static function &getAdapter($config = null) {
		if (is_null(self::$_adapter) && !is_null($config)) {
			$type = 'Plutonium_Database_Adapter_' . $config->driver;
			
			self::$_adapter = new $type($config);
		}
		
		return self::$_adapter;
	}
	
	public static function getTable($name) {
		$name = strtolower($name);
		$type = ucfirst($name) . 'Table';
		
		$path = Plutonium_Application::getPath();
		$file = 'models' . DS . 'tables' . DS . $name;
		
		$file_php = $path . DS . $file . '.php';
		$file_xml = $path . DS . $file . '.xml';
		
		if (!is_file($file_php) || !is_file($file_xml)) {
			$path = Plutonium_Module::getPath() . DS
				  . Plutonium_Module::getName();
			
			$file_php = $path . DS . $file . '.php';
			$file_xml = $path . DS . $file . '.xml';
		}
		
		if (is_file($file_xml)) {
			$cfg = new Plutonium_Object();
			$cfg->driver = self::getAdapter()->driver;
			
			$doc = new DOMDocument();
			$doc->preserveWhiteSpace = false;
			$doc->load($file_xml);
			
			$xpath = new DOMXPath($doc);
			
			$table = $xpath->query('/table')->item(0);
			
			$cfg->name       = $table->getAttribute('name');
			$cfg->prefix     = $table->getAttribute('prefix');
			$cfg->timestamps = $table->getAttribute('timestamps');
			
			if (empty($cfg->prefix)) $cfg->prefix = 'mod';
			
			$fields = array();
			
			$nodes = $xpath->query('/table/field');
			foreach ($nodes as $field) {
				$fields[] = new Plutonium_Object(array(
					'name' => $field->getAttribute('name'),
					'type' => $field->getAttribute('type'),
					'size' => $field->getAttribute('size'),
				));
			}
			
			$cfg->fields = $fields;
			
			$refs = array();
			
			$nodes = $xpath->query('/table/ref');
			foreach ($nodes as $ref) {
				$refs[] = new Plutonium_Object(array(
					'name'   => $ref->getAttribute('name'),
					'table'  => $ref->getAttribute('table'),
					'prefix' => $ref->getAttribute('prefix')
				));
			}
			
			$cfg->refs = $refs;
			
			$xrefs = array();
			
			$nodes = $xpath->query('/table/xref');
			foreach ($nodes as $xref) {
				$temp = new Plutonium_Object(array(
					'driver'     => $cfg->driver,
					'prefix'     => $cfg->prefix,
					'suffix'     => 'xref',
					'name'       => $xref->getAttribute('name'),
					'timestamps' => $xref->getAttribute('timestamps'),
					'refs'       => array(
						new Plutonium_Object(array(
							'name'   => $cfg->name,
							'table'  => $cfg->name,
							'prefix' => $cfg->prefix
						)),
						new Plutonium_Object(array(
							'name'   => $xref->getAttribute('table'),
							'table'  => $xref->getAttribute('table'),
							'prefix' => $xref->getAttribute('prefix')
						))
					)
				));
				
				$fields = array();
				
				$subnodes = $xpath->query('field', $xref);
				foreach ($subnodes as $field) {
					$fields[] = new Plutonium_Object(array(
						'name' => $field->getAttribute('name'),
						'type' => $field->getAttribute('type'),
						'size' => $field->getAttribute('size')
					));
				}
				
				$temp->fields = $fields;
				
				$xrefs[] = $temp;
			}
			
			$cfg->xrefs = $xrefs;
			
			if (is_file($file_php)) require_once $file_php;
			else $type = 'Plutonium_Database_Table_Default';
			
			return new $type($cfg);
		}
		
		return null;
	}
}

?>