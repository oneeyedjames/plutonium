<?php

use Plutonium\Application\View;

class DomainsView extends View {
	public function defaultLayout() {
		$model = $this->getModel();
		$domains = $model->find();

		$hosts = array_unique(array_map(function($domain) {
			return $domain->host_id;
		}, $domains));

		$model = $this->getModel('hosts');
		$hosts = $model->find(['id' => $hosts]);
		$hosts = array_combine(array_map(function($host) {
			return $host->id;
		}, $hosts), $hosts);

		$this->setRef('domains', $domains);
		$this->setRef('hosts', $hosts);
	}

	public function formLayout() {
		$model = $this->getModel();
		$domain = $model->getTable()->make();
		$url = [$this->name];

		if ($id = $this->module->request->id) {
			$domain = $model->find($id);
			$url[] = $id;
		}

		$model = $this->getModel('hosts');
		$hosts = $model->find();

		$this->setRef('domain', $domain);
		$this->setVal('action', FS . implode(FS, $url) . FS);
		$this->setRef('hosts', $hosts);
	}
}
