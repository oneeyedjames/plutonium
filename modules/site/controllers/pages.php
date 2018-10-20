<?php

use Plutonium\Application\Controller;

class PagesController extends Controller {
	public function defaultAction() {
		$view = $this->getView();
		$view->layout = 'item';

		$this->itemAction();
	}

	public function itemAction() {
		$slug = $this->_module->request->get('slug', '');

		if (!empty($slug))
			$this->getView()->slug = $slug;
	}
}
