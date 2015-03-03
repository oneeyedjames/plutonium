<?php

class PagesView extends Plutonium_Module_View {
	public function detailsLayout() {
		$request = $this->_module->request;

		$model = $this->getModel();

		$slug = $this->getVar('slug', $request->get('slug', ''));

		if (empty($slug))
			$pages = $model->find(1);
		else
			$pages = $model->find(array('slug' => $slug));

		$page = array_shift($pages);
		$page->body = paragraphize($page->body);

		$this->setRef('page', $page);

		$document = $this->_module->application->document;
		$document->setTitle($page->title);
	}
}
