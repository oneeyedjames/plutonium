<?php

use Plutonium\Application\Controller;

class DomainsController extends Controller {
	public function createAction() {
		$domain = $this->request->get('domain');

		$model = $this->getModel();
		$model->save($domain);

		$this->redirect = PU_URL_BASE . FS . $this->name . FS;
	}

	public function updateAction() {
		$domain = $this->request->get('domain');
		$domain['id'] = $this->request->id;

		$model = $this->getModel();
		$model->save($domain);

		$this->redirect = PU_URL_BASE . FS . $this->name . FS;
	}

	public function deleteAction() {
		$model = $this->getModel();
		$model->delete($this->request->id);

		$this->redirect = PU_URL_BASE . FS . $this->name . FS;
	}
}
