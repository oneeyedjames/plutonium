<?php

class HttpApplication extends Plutonium_Application {
	protected static $_instance = null;
	
	public static function &getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new self();
		
		return self::$_instance;
	}
	
	public function initialize() {
		$request  =& Plutonium_Request::getInstance();
		$registry =& Plutonium_Registry::getInstance();
		
		$table = Plutonium_Database_Helper::getTable('themes');
		$rows  = $table->find(array('default' => 1));
		
		if (!empty($rows))
			$registry->config->set('theme', $rows[0]->slug);
		
		if ($request->has('host') && !$request->has('module')) {
			$table = Plutonium_Database_Helper::getTable('hosts');
			$rows  = $table->find(array('slug' => $request->host));
			
			if (empty($host)) {
				$table = Plutonium_Database_Helper::getTable('modules');
				$rows  = $table->find(array('slug' => $request->host));
				
				if (!empty($rows)) {
					$request->set('module', $request->host);
					$request->del('host');
				}
			}
		}
		
		if (!$request->has('host')) {
			$table = Plutonium_Database_Helper::getTable('hosts');
			$rows  = $table->find(array('default' => 1));
			
			if (!empty($rows))
				$request->set('host', $rows[0]->slug);
		}
		
		if (!$request->has('module')) {
			$table = Plutonium_Database_Helper::getTable('modules');
			$rows  = $table->find(array('default' => 1));
			
			if (!empty($rows))
				$request->set('module', $rows[0]->slug);
		}
		
		// Going for broke with hard-coded defaults
		$request->def('host',   'main');
		$request->def('module', 'site');
		
		$table = Plutonium_Database_Helper::getTable('modules');
		$rows  = $table->find(array('slug' => $request->module));
		
		if (!empty($rows)) {
			$xref = $rows[0]->modules_widgets;
			
			if (!empty($xref)) {
				$widgets = array();
				
				foreach ($xref as $row) {
					$widget = $row->widgets;
					
					if (!is_null($widget)) {
						$widgets[$row->location][$row->position] = $widget->slug;
					}
				}
				
				$registry->config->set('widgets', $widgets);
			}
		}
		
		// Going for broke with hard-coded defaults
		$registry->config->def('theme',   'charcoal');
		$registry->config->def('widgets', array());
		
		parent::initialize();
	}
}

?>