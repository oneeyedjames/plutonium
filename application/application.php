<?php

class HttpApplication extends Plutonium_Application {
	protected static $_instance = null;
	
	public static function &getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new self();
		
		return self::$_instance;
	}
	
	public function initialize() {
		$database =& Plutonium_Database_Helper::getAdapter();
		$registry =& Plutonium_Registry::getInstance();
		$request  =& Plutonium_Request::getInstance();
		
		// Select Layout
		$field = $database->quoteSymbol('slug');
		$table = $database->quoteSymbol('app_themes');
		$key   = $database->quoteSymbol('default');
		$value = 1;
		
		$sql = "SELECT $field FROM $table "
			 . "WHERE $table.$key = $value";
		
		$result = $database->query($sql);
		$record = $result->fetchObject();
		$result->close();
		
		$registry->config->set('theme', @$record->slug);
		
		// Select Widgets
		$modules   = $database->quoteSymbol('app_modules');
		$widgets   = $database->quoteSymbol('app_widgets');
		$xref      = $database->quoteSymbol('app_module_widget_xref');
		$id        = $database->quoteSymbol('id');
		$slug      = $database->quoteSymbol('slug');
		$module_id = $database->quoteSymbol('module_id');
		$widget_id = $database->quoteSymbol('widget_id');
		$location  = $database->quoteSymbol('location');
		$position  = $database->quoteSymbol('position');
		
		$module_slug = $database->quoteString($request->get('module'));
		
		$sql = "SELECT $widgets.$slug, $xref.$location, $xref.$position "
			 . "FROM $xref "
			 . "INNER JOIN $modules ON $xref.$module_id IN ($modules.$id, 0) "
			 . "INNER JOIN $widgets ON $xref.$widget_id = $widgets.$id "
			 . "WHERE $modules.$slug = $module_slug "
			 . "ORDER BY $xref.$location, $xref.$position";
		
		$result  = $database->query($sql);
		$records = $result->fetchAll('object');
		$result->close();
		
		$widgets = array();
		
		foreach ($records as $record) {
			$widgets[$record->location][$record->position] = $record->slug;
		}
		
		$registry->config->set('widgets', $widgets);
		
		parent::initialize();
	}
}

?>