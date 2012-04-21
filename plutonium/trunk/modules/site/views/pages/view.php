<?php

class PagesView extends Plutonium_Module_View_Abstract {
	public function detailsLayout() {
		$request =& Plutonium_Request::getInstance();
		
		$model =& $this->getModel();
		
		$slug = $this->getVar('slug', $request->get('slug', ''));
		$page = $model->find((int) $slug);
		
		$this->setRef('page', $page);
		
		$document =& Plutonium_Document_Helper::getDocument();
		$document->setTitle($page->title);
	}
}
