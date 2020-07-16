<?php

use Plutonium\Application\View;

abstract class ComponentsView extends View {
	public function defaultLayout() {
		$model = $this->getModel();

		$components = $model->find();
		$names = [];

		foreach ($components as $component)
			$names[] = $component->slug;

		$this->setRef('enabled', $components);

		$components = [];

		foreach (glob($this->getPath() . '/*') as $path) {
			if (is_dir($path)) {
				$name = basename($path);

				if (!in_array($name, $names))
					$components[$name] = $this->getMetadata($name);
			}
		}

		$this->setRef('available', $components);
	}

	public function buildLink($component) {
		return PU_URL_BASE . FS . $this->module->getRouter()->build([
			'resource' => $this->name,
			'id' => $component->id
		]);
	}

	abstract protected function getPath();
	abstract protected function getMetadata($name);
}
