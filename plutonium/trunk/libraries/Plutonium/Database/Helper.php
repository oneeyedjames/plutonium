<?php

class Plutonium_Database_Helper {
	protected static $_adapter = null;
	
	protected static $_tables = array();
	
	protected static $_refs = array();
	protected static $_xref = array();
	
	public static function &getAdapter($config = null) {
		if (is_null(self::$_adapter) && !is_null($config)) {
			$type = 'Plutonium_Database_Adapter_' . $config->driver;
			self::$_adapter = new $type($config);
		}
		
		return self::$_adapter;
	}
	
	public static function getTable($name) {
		if (!isset(self::$_tables[$name])) {
			$name = strtolower($name);
			$type = ucfirst($name) . 'Table';
		
			$path = Plutonium_Application::getPath();
			$file = 'models' . DS . 'tables' . DS . $name;
		
			$file_php = $path . DS . $file . '.php';
			$file_xml = $path . DS . $file . '.xml';
		
			if (!is_file($file_xml)) {
				$path = Plutonium_Module::getPath() . DS
					  . Plutonium_Module::getName();
			
				$file_php = $path . DS . $file . '.php';
				$file_xml = $path . DS . $file . '.xml';
			}
		
			if (is_file($file_xml)) {
				$cfg = new Plutonium_Object();
				$cfg->driver = self::getAdapter()->driver;
			
				$doc = new DOMDocument();
				$doc->preserveWhiteSpace = true;
				$doc->formatOutput = true;
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
						'name'   => $field->getAttribute('name'),
						'type'   => $field->getAttribute('type'),
						'size'   => $field->getAttribute('size'),
						'length' => $field->getAttribute('length'),
					));
				}
			
				$cfg->fields = $fields;
			
				$refs = array();
			
				$nodes = $xpath->query('/table/ref');
				foreach ($nodes as $node) {
					$ref = new Plutonium_Object(array(
						'name'   => $node->getAttribute('name'),
						'table'  => $node->getAttribute('table'),
						'prefix' => $node->getAttribute('prefix')
					));
					
					$refs[] = $ref;
					
					self::$_refs[$ref->table][$ref->alias] = new Plutonium_Object(array(
						'table' => $cfg->name,
						'alias' => $ref->name
					));
				}
				
				$cfg->refs = $refs;
			
				$xrefs = array();
			
				$nodes = $xpath->query('/table/xref');
				foreach ($nodes as $node) {
					$xref = new Plutonium_Object(array(
						'driver'     => $cfg->driver,
						'prefix'     => $cfg->prefix,
						'suffix'     => 'xref',
						'name'       => $cfg->name . '_' . $node->getAttribute('table'),
						'timestamps' => $node->getAttribute('timestamps'),
						'xrefs'      => array(),
						'refs'       => array(
							new Plutonium_Object(array(
								'name'   => $cfg->name,
								'table'  => $cfg->name,
								'prefix' => $cfg->prefix
							)),
							new Plutonium_Object(array(
								'name'   => $node->getAttribute('table'),
								'table'  => $node->getAttribute('table'),
								'prefix' => $node->getAttribute('prefix')
							))
						)
					));
				
					$fields = array();
				
					$subnodes = $xpath->query('field', $node);
					foreach ($subnodes as $subnode) {
						$fields[] = new Plutonium_Object(array(
							'name' => $subnode->getAttribute('name'),
							'type' => $subnode->getAttribute('type'),
							'size' => $subnode->getAttribute('size')
						));
					}
				
					$xref->fields = $fields;
				
					$xrefs[] = $xref;
				}
			
				$cfg->xrefs = $xrefs;
			
				self::$_tables[$name] = Plutonium_Loader::getClass($file_php, $type, 'Plutonium_Database_Table', $cfg);
			}
		}
		
		return @self::$_tables[$name];
	}
	
	public static function getRefs($table) {
		if (array_key_exists($table, self::$_refs)) {
			$refs = self::$_refs[$table];
		
			unset(self::$_refs[$table]);
			
			return $refs;
		}
		
		return array();
	}
}

?>