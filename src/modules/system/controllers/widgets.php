<?php

require_once __DIR__ . '/components.php';

use Plutonium\Application\Widget;

class WidgetsController extends ComponentsController {
	protected function getMetadata($name) {
		return Widget::getMetadata($name);
	}

	protected function getInstance($name) {
		return Widget::newInstance($this->module->application, $name);
	}
}
