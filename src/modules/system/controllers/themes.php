<?php

require_once __DIR__ . '/components.php';

use Plutonium\Application\Theme;

class ThemesController extends ComponentsController {
	protected function getMetadata($name) {
		return Theme::getMetadata($name);
	}

	protected function getInstance($name) {
		return Theme::newInstance($this->module->application, $name);
	}
}
