<?php

use Plutonium\Application\Application;

class SetupApplication extends Application {
	public function initialize() {
		$this->request->host     = 'main';
		$this->request->module   = 'system';
		$this->request->resource = 'setup';

		$this->config->theme = 'charcoal';

		parent::initialize();
	}
}
