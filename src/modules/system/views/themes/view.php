<?php

require_once dirname(__DIR__) . '/components.php';

use Plutonium\Application\Theme;

class ThemesView extends ComponentsView {
	protected function getPath() {
		// return Theme::getPath();
		return PU_PATH_BASE . '/themes';
	}

	protected function getMetadata($name) {
		return Theme::getMetadata($name);
	}
}
