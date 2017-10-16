<?php

use function Plutonium\Functions\paragraphize;

use Plutonium\Application\View;

class PagesView extends View {
	public function itemLayout() {
		$request = $this->_module->request;

		$model = $this->getModel();

		$slug = $this->getVar('slug', $request->get('slug', ''));
		$args = empty($slug) ? array('parent' => 0) : compact('slug');
		$page = $model->find($args, null, 1);

		if ($page) $page->body = paragraphize($page->body);

		$this->setRef('page', $page);

		$document = $this->_module->application->document;
		$document->title = $page->title;
	}
}
