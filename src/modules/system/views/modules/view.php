<?php

use Plutonium\Application\Module;
use Plutonium\Application\View;

class ModulesView extends View {
	public function defaultLayout() {
		$modules = [];

		foreach (glob(PU_PATH_BASE . '/modules/*') as $path) {
			if (is_dir($path)) {
				$name = basename($path);
				$modules[$name] = Module::getMetadata($name);
			}
		}

		$this->setRef('modules', $modules);
	}
}