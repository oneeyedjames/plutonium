<?php

class PagesController extends Plutonium_Module_Controller {
	public function defaultAction() {
		$view =& $this->getView();
		$view->layout = 'details';
		
		$this->detailsAction();
	}
	
	public function detailsAction() {
		$request =& Plutonium_Request::getInstance();
		
		$path = $request->get('path', array());
		
		if (!empty($path))
			$this->getView()->slug = $path[0];
	}
}
