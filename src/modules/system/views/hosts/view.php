<?php

use Plutonium\Application\View;

class HostsView extends View {
	public function defaultLayout() {
		$model = $this->getModel();
		$hosts = $model->find();

		$this->setRef('hosts', $hosts);
	}

	public function itemLayout() {
		$model = $this->getModel();
		$host = $model->find($this->module->request->id);

		$model = $this->getModel('domains');
		$domains = $model->find([
			'host_id' => $host->id
		]);

		$this->setRef('host', $host);
		$this->setRef('domains', $domains);
	}

	public function formLayout() {
		$model = $this->getModel();
		$host = $model->getTable()->make();
		$url = ['hosts'];

		if ($id = $this->module->request->id) {
			$host = $model->find($id);
			$url[] = $id;
		}

		$this->setRef('host', $host);
		$this->setVal('action', FS . implode(FS, $url) . FS);
	}
}
