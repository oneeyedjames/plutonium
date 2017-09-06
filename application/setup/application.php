<?php

class SetupApplication extends Plutonium_Application {
	public function initialize() {
		// $database = Plutonium_Database_Adapter::getInstance($config->database);
		//
		// Plutonium_Database_Table::getInstance('hosts');
		// Plutonium_Database_Table::getInstance('domains');
		// Plutonium_Database_Table::getInstance('users');
		// Plutonium_Database_Table::getInstance('groups');
		//
		// Plutonium_Database_Table::getInstance('themes');
		// Plutonium_Database_Table::getInstance('modules');
		// Plutonium_Database_Table::getInstance('widgets')->find(1)->module(array());

		$this->request->host   = 'main';
		$this->request->module = 'setup';

		$this->config->theme = 'charcoal';

		parent::initialize();
	}
}
