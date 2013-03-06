<?php

class PagesView extends Plutonium_Module_View {
	public function detailsLayout() {
		$request =& Plutonium_Request::getInstance();
		
		$model =& $this->getModel();
		
		$slug = $this->getVar('slug', $request->get('slug', ''));
		
		if (empty($slug)) $slug = 1;
		
		$page = $model->find(intval($slug));
		$page->body = paragraphize($page->body);
		
		$this->setRef('page', $page);
		
		$document =& Plutonium_Document::getInstance();
		$document->setTitle($page->title);
	}
}
