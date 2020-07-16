<?php

use Plutonium\Application\Controller;

abstract class ComponentsController extends Controller {
	public function createAction() {
		$slug = strtolower($this->request->name);

		if ($meta = $this->getMetadata($slug)) {
			$data = [
				'slug' => $slug,
				'name' => $meta['package'],
				'descrip' => $meta['description']
			];

			$model = $this->getModel();
			$model->save($data);
		}

		$this->redirect = PU_URL_BASE . FS . $this->name . FS;
	}

	public function deleteAction() {
		$model = $this->getModel();
		$model->delete($this->request->id);

		$this->redirect = PU_URL_BASE . FS . $this->name . FS;
	}

	abstract protected function getMetadata($name);
}
