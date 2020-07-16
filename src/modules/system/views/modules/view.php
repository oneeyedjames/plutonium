<?php

require_once dirname(__DIR__) . '/components.php';

use Plutonium\Application\Module;

class ModulesView extends ComponentsView {
	protected function getPath() {
		return Module::getPath();
	}

	protected function getMetadata($name) {
		return Module::getMetadata($name);
	}
}
