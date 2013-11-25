<?php

class PagesView extends Plutonium_Module_View {
	public function detailsLayout() {
		$request =& Plutonium_Request::getInstance();

		$model =& $this->getModel();

		$slug = $this->getVar('slug', $request->get('slug', ''));

		if (empty($slug))
			$page = $model->find(1);
		else
			$page = array_shift($model->find(array('slug' => $slug)));

		$page->body = paragraphize($page->body);

		$this->setRef('page', $page);

		$document =& Plutonium_Document::getInstance();
		$document->setTitle($page->title);
	}
}
