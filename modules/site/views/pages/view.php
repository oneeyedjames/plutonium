<?php

use function Plutonium\Functions\paragraphize;

use Plutonium\Application\View;

class PagesView extends View {
	public function detailsLayout() {
		$request = $this->_module->request;

		$model = $this->getModel();

		$slug = $this->getVar('slug', $request->get('slug', ''));

		if (empty($slug))
			$page = $model->find(1);
		else
			$page = $model->find(array('slug' => $slug), null, 1);

		if ($page)
			$page->body = paragraphize($page->body);

		$this->setRef('page', $page);

		$document = $this->_module->application->document;
		$document->title = $page->title;
	}
}
