<?php

class PagesView extends Plutonium_Module_View {
	public function detailsLayout() {
		$request = $this->_module->request;

		$model = $this->getModel();

		$slug = $this->getVar('slug', $request->get('slug', ''));

		if (empty($slug))
			$page = $model->find(1);
		else
			$page = $model->find(array('slug' => $slug), null, 1);

		$page->body = paragraphize($page->body);

		$this->setRef('page', $page);

		$document = $this->_module->application->document;
		$document->setTitle($page->title);
	}
}
