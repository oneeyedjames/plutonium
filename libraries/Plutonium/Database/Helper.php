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
		
		$file_php = $path . DS . 'tables' . DS . $name . '.php';
		$file_xml = $path . DS . 'tables' . DS . $name . '.xml';
		
		if (!is_file($file_php) || !is_file($file_xml)) {
			$path = Plutonium_Module_Helper::getPath() . DS
				  . Plutonium_Module_Helper::getName();
			
			$file_php = $path . DS . 'models' . DS . 'tables' . DS . $name . '.php';
			$file_xml = $path . DS . 'models' . DS . 'tables' . DS . $name . '.xml';
		}
		
		if (is_file($file_xml)) {
			$cfg = new Plutonium_Object();
			
			$cfg->driver = self::getAdapter()->driver;
			
			$doc = new DOMDocument();
			$doc->preserveWhiteSpace = FALSE;
			$doc->load($file_xml);
			
			//$schema = realpath(dirname(__FILE__) . DS . 'Table.xsd');
			//$doc->schemaValidate($schema);
			
			$xpath = new DOMXPath($doc);
			
			$cfg->name = $xpath->query('/table')->item(0)->getAttribute('name');
			$cfg->prefix = $xpath->query('/table')->item(0)->getAttribute('prefix');
			$cfg->timestamps = $xpath->query('/table')->item(0)->getAttribute('timestamps');
			
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
			foreach ($nodes as $node) {
				$refs[] = new Plutonium_Object(array(
					'name'   => $node->getAttribute('name'),
					'table'  => $node->getAttribute('table'),
					'prefix' => $node->getAttribute('prefix')
				));
			}
			
			$cfg->refs = $refs;
			
			if (is_file($file_php)) require_once $file_php;
			else $type = 'Plutonium_Database_Table_Default';
			
			return new $type($cfg);
		}
		
		return NULL;
	}
	
	public static function getTableDelegate($driver, $table) {
		$type = 'Plutonium_Database_Table_Delegate_' . $driver;
		
		return new $type($table);
	}
}

?>