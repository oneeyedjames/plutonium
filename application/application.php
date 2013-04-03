<?php

class HttpApplication extends Plutonium_Application {
	protected static $_instance = null;
	
	public static function &getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new self();
		
		return self::$_instance;
	}

	protected $_cache_valid;
	protected $_cache_path;
	
	public function initialize() {
		$request  =& Plutonium_Request::getInstance();
		$registry =& Plutonium_Registry::getInstance();
		
		// Validate host/module dilemma
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
		
		// Lookup default host
		if (!$request->has('host')) {
			$table = Plutonium_Database_Helper::getTable('hosts');
			$rows  = $table->find(array('default' => 1));
			
			if (!empty($rows))
				$request->set('host', $rows[0]->slug);
		}
		
		// Lookup default module
		if (!$request->has('module')) {
			$table = Plutonium_Database_Helper::getTable('modules');
			$rows  = $table->find(array('default' => 1));
			
			if (!empty($rows))
				$request->set('module', $rows[0]->slug);
		}
		
		// Lookup default theme
		if (!$registry->config->has('theme')) {
			$table = Plutonium_Database_Helper::getTable('themes');
			$rows  = $table->find(array('default' => 1));
		
			if (!empty($rows))
				$registry->config->set('theme', $rows[0]->slug);
		}
		
		// Go for broke with hard-coded defaults
		$request->def('host',   'main');
		$request->def('module', 'site');
		
		$registry->config->def('theme', 'charcoal');
		
		parent::initialize();
		
		/* $url = parse_url($this->_module->getPermalink());
		
		$this->_cache_path = PU_PATH_BASE . '/cache'
			. FS . $url['host'] . $url['path'];
		
		$this->_cache_valid = file_exists($this->_cache_path) &&
			filemtime($this->_cache_path) > time() - 900;
		
		if ($this->_cache_valid) return; */
		
		// Load widgets
		$table = Plutonium_Database_Helper::getTable('modules');
		$rows  = $table->find(array('slug' => $request->module));
		
		if (!empty($rows)) {
			$widgets = array();
			
			foreach ($rows[0]->widget as $widget) {
				$xref = $widget->xref->module;
				$this->addWidget($xref->location, $widget->slug);
			}
		}
	}
	
	/* public function execute() {
		$request  =& Plutonium_Request::getInstance();
		$request->def('format', 'html');
		
		header('Content-type: text/' . $request->format);
		
		if ($this->_cache_valid) {
			$content = file_get_contents($this->_cache_path);
		} else {
			if (!file_exists(dirname($this->_cache_path)))
				mkdir(dirname($this->_cache_path), 0755, true);
			
			if (!file_exists(dirname($this->_cache_path)))
				Plutonium_Error_Helper::triggerWarning('Could not write to cache: ' . dirname($this->_cache_path));
			
			ob_start();
			
			ob_clean();
			
			parent::execute();
			
			$content = ob_get_clean();
			
			if (file_exists(dirname($this->_cache_path)))
				file_put_contents($this->_cache_path, $content);
			
			ob_end_clean();
		}
		
		echo $content;
	} */
}

?>