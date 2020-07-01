<?php

use function Plutonium\Functions\paragraphize;

use Plutonium\Application\View;
use Plutonium\AccessObject;

class PagesView extends View {
	public function itemLayout() {
		$request = $this->_module->request;

		$model = $this->getModel();

		$slug = $this->getVar('slug', $request->get('slug', ''));
		$args = empty($slug) ? ['parent_id' => 0] : compact('slug');
		$page = $model->find($args, null, 1);

		if ($page) $page->body = paragraphize($page->body);

		$this->setRef('page', $page);

		$document = $this->_module->application->document;
		$document->title = $page->title;
	}

	public function formLayout() {
		$request = $this->_module->request;

		$model = $this->getModel();

		$slug = $this->getVar('slug', $request->get('slug', ''));

		if (!empty($slug)) {
			$args = compact('slug');
			$page = $model->find($args, null, 1);
		} else {
			$page = new AccessObject([
				'name' => '',
				'body' => ''
			]);
		}

		$this->setRef('page', $page);
	}
}
