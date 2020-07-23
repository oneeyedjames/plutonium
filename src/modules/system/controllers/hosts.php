<?php

use Plutonium\Application\Controller;

class HostsController extends Controller {
	public function createAction() {
		$data = $this->request->get('host', [], 'post');

		$model = $this->getModel();

		var_dump($data);exit;
	}

	public function updateAction() {
		$data = $this->request->get('host', [], 'post');
		$host = $this->request->get('host', []);

		var_dump($this->request);exit;
	}

	public function deleteAction() {
		var_dump($this->request->id);exit;
	}
}
