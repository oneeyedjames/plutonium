<?php

class Plutonium_Database_Helper {
	protected static $_adapter = null;

	protected static $_tables = array();
	protected static $_xref_tables = array();

	protected static $_refs = array();

	public static function getAdapter($config = null) {
		if (is_null(self::$_adapter) && !is_null($config)) {
			$type = 'Plutonium_Database_Adapter_' . $config->driver;
			self::$_adapter = new $type($config);
		}

		return self::$_adapter;
	}

	public static function getTable($name, $module = null) {
		if (!isset(self::$_tables[$name])) {
			$name = strtolower($name);
			$type = ucfirst($name) . 'Table';

			$file = 'models' . DS . 'tables' . DS . $name;

			if (is_null($module))
				$path = Plutonium_Application::getPath();
			else
				$path = Plutonium_Module::getPath() . DS . strtolower($module);

			$file_php = $path . DS . $file . '.php';
			$file_xml = $path . DS . $file . '.xml';

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

				if (!is_null($module)) {
					$cfg->prefix = 'mod';
					$cfg->module = $module;
				}

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

				//$xrefs = array();

				$nodes = $xpath->query('/table/xref');
				foreach ($nodes as $node) {
					$name   = $node->getAttribute('name');
					$alias  = $node->getAttribute('alias');
					$table  = $node->getAttribute('table');
					$prefix = $node->getAttribute('prefix');

					if (empty($name))   $name   = $table;
					if (empty($alias))  $alias  = $cfg->name;
					if (empty($prefix)) $prefix = $cfg->prefix;

					$xref_cfg = new Plutonium_Object(array(
						'driver'     => $cfg->driver,
						'prefix'     => $cfg->prefix,
						'suffix'     => 'xref',
						'name'       => $alias . '_' . $name,
						'timestamps' => $node->getAttribute('timestamps'),
						'refs'       => array(
							new Plutonium_Object(array(
								'name'   => $alias,
								'table'  => $cfg->name,
								'prefix' => $cfg->prefix
							)),
							new Plutonium_Object(array(
								'name'   => $name,
								'table'  => $table,
								'prefix' => $prefix
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

					$xref_cfg->fields = $fields;

					//$xrefs[] = $xref;

					$xref_table = new Plutonium_Database_Table($xref_cfg);

					self::$_xref_tables[$table][$alias] =& $xref_table;
					self::$_xref_tables[$cfg->name][$name] =& $xref_table;
				}

				//$cfg->xrefs = $xrefs;

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

	public static function getXRefs($table) {
		if (array_key_exists($table, self::$_xref_tables)) {
			return self::$_xref_tables[$table];

			/* $xrefs = self::$_xref_tables[$table];

			unset(self::$_xref_tables[$table]);

			return $xrefs; */
		}

		return array();
	}
}

?>