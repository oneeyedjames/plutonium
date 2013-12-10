<?php

class PagesController extends Plutonium_Module_Controller {
	public function defaultAction() {
		$view = $this->getView();
		$view->layout = 'details';

		$this->detailsAction();
	}

	public function detailsAction() {
		$slug = $this->_module->request->get('slug', '');

		if (!empty($slug))
			$this->getView()->slug = $slug;
	}
}
