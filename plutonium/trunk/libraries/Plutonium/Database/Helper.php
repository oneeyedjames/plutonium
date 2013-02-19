<?php

class Plutonium_Database_Helper {
	protected static $_adapter = NULL;
	
	public static function &getAdapter($config = NULL) {
		if (is_null(self::$_adapter) && !is_null($config)) {
			$type = 'Plutonium_Database_Adapter_' . $config->driver;
			
			self::$_adapter = new $type($config);
		}
		
		return self::$_adapter;
	}
	
	public static function getTable($name) {
		$name = strtolower($name);
		$type = ucfirst($name) . 'Table';
		
		$path = Plutonium_Application_Helper::getPath();
		$file = 'models' . DS . 'tables' . DS . $name;
		
		$file_php = $path . DS . $file . '.php';
		$file_xml = $path . DS . $file . '.xml';
		
		if (!is_file($file_php) || !is_file($file_xml)) {
			$path = Plutonium_Module_Helper::getPath() . DS
				  . Plutonium_Module_Helper::getName();
			
			$file_php = $path . DS . $file . '.php';
			$file_xml = $path . DS . $file . '.xml';
		}
		
		if (is_file($file_xml)) {
			$cfg = new Plutonium_Object();
			$cfg->driver = self::getAdapter()->driver;
			
			$doc = new DOMDocument();
			$doc->preserveWhiteSpace = FALSE;
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
			
			$nodes = $xpath->query('/table/xref');
			foreach ($nodes as $xref) {
				
			}
			
			if (is_file($file_php)) require_once $file_php;
			else $type = 'Plutonium_Database_Table_Default';
			
			return new $type($cfg);
		}
		
		return NULL;
	}
}

?>