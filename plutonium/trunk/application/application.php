<?php

class HttpApplication extends Plutonium_Application {
	protected static $_instance = null;

	/* public static function getInstance($config = null) {
		if (is_null(self::$_instance) && !is_null($config))
			self::$_instance = new self($config);

		return self::$_instance;
	} */

	public function initialize() {
		// Validate host/module dilemma
		if (isset($this->request->host) && !isset($this->request->module)) {
			$table = Plutonium_Database_Helper::getTable('hosts');
			$rows  = $table->find(array('slug' => $request->host));

			if (empty($host)) {
				$table = Plutonium_Database_Helper::getTable('modules');
				$rows  = $table->find(array('slug' => $request->host));

				if (!empty($rows)) {
					$this->request->module = $this->request->host;
					unset($this->request->host);
				}
			}
		}

		// Lookup default host
		if (!isset($this->request->host)) {
			$table = Plutonium_Database_Helper::getTable('hosts');
			$rows  = $table->find(array('default' => 1));

			if (!empty($rows))
				$this->request->host = $rows[0]->slug;
		}

		// Lookup default module
		if (!isset($this->request->module)) {
			$table = Plutonium_Database_Helper::getTable('modules');
			$rows  = $table->find(array('default' => 1));

			if (!empty($rows))
				$this->request->module = $rows[0]->slug;
		}

		// Lookup default theme
		if (!isset($this->config->theme)) {
			$table = Plutonium_Database_Helper::getTable('themes');
			$rows  = $table->find(array('default' => 1));

			if (!empty($rows))
				$this->config->theme = $rows[0]->slug;
		}

		// Go for broke with hard-coded defaults
		$this->request->def('host',   'main');
		$this->request->def('module', 'site');

		$this->config->def('theme', 'charcoal');

		parent::initialize();

		// Load widgets
		$table = Plutonium_Database_Helper::getTable('modules');
		$rows  = $table->find(array('slug' => $this->request->module));

		if (!empty($rows)) {
			$widgets = array();

			foreach ($rows[0]->widget as $widget) {
				$xref = $widget->xref->module;
				$this->addWidget($xref->location, $widget->slug);
			}
		}
	}
}

?>