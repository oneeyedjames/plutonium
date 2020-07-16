<?php

require_once dirname(__DIR__) . '/components.php';

use Plutonium\Application\Widget;

class WidgetsView extends ComponentsView {
	protected function getPath() {
		// return Widget::getPath();
		return PU_PATH_BASE . '/widgets';
	}

	protected function getMetadata($name) {
		return Widget::getMetadata($name);
	}
}
