<?php

use Plutonium\AccessObject;
use Plutonium\Application\Controller;

class PagesController extends Controller {
	public function createAction() {
		$data = new AccessObject([
			'name'   => $this->request->name,
			'body'   => $this->request->body,
			'parent' => $this->request->parent
		]);

		$model = $this->getModel();

		if ($page = $model->save($data)) {
			$this->redirect = FS . $page->slug;
		} else {
			trigger_error('Page could not be saved.', E_USER_ERROR);
		}
	}
}
