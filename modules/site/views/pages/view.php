<?php

class PagesView extends Plutonium_Module_View {
	public function detailsLayout() {
		$request = $this->_module->request;

		$model =& $this->getModel();

		$slug = $this->getVar('slug', $request->get('slug', ''));

		if (empty($slug))
			$page = $model->find(1);
		else
			$page = array_shift($model->find(array('slug' => $slug)));

		$page->body = paragraphize($page->body);

		$this->setRef('page', $page);

		$document =& HttpApplication::getInstance()->document;
		$document->setTitle($page->title);
	}
}
