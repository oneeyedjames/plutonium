<?php

require_once __DIR__ . '/components.php';

use Plutonium\Application\Module;

class ModulesController extends ComponentsController {
	protected function getMetadata($name) {
		return Module::getMetadata($name);
	}

	protected function getInstance($name) {
		return Module::newInstance($this->module->application, $name);
	}
}
