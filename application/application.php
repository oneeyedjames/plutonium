<?php

class HttpApplication extends Plutonium_Application {
	protected static $_instance = null;

	public function initialize() {
		if (!isset($this->request->host)) {

			// Check for mapped domain
			$domain = explode('.', $this->request->get('HTTP_HOST', null, 'server'));
			// $domain = array('site', 'main', 'pu');

			$aliases = array();

			while (count($domain) > 1) {
				$aliases[] = implode('.', $domain);
				array_shift($domain);
			}

			$table = Plutonium_Database_Table::getInstance('domains');
			$rows  = $table->find(array('domain' => $aliases));

			if (!empty($rows))
				$this->request->host = $rows[0]->host->slug;
		} elseif (!isset($this->request->module)) {

			// Validate host/module dilemma
			$table = Plutonium_Database_Table::getInstance('hosts');
			$rows  = $table->find(array('slug' => $this->request->host));

			if (empty($rows)) {
				$table = Plutonium_Database_Table::getInstance('modules');
				$rows  = $table->find(array('slug' => $this->request->host));

				if (!empty($rows)) {
					$this->request->module = $this->request->host;
					unset($this->request->host);
				}
			}
		}

		// Lookup default host
		if (!isset($this->request->host)) {
			$table = Plutonium_Database_Table::getInstance('hosts');
			$rows  = $table->find(array('default' => 1));

			if (!empty($rows))
				$this->request->host = $rows[0]->slug;
		}

		// Lookup default module
		if (!isset($this->request->module)) {
			$table = Plutonium_Database_Table::getInstance('modules');
			$rows  = $table->find(array('default' => 1));

			if (!empty($rows))
				$this->request->module = $rows[0]->slug;
		}

		// Lookup default theme
		if (!isset($this->config->theme)) {
			$table = Plutonium_Database_Table::getInstance('themes');
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
		$table = Plutonium_Database_Table::getInstance('modules');
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
