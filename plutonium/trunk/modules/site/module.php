<?php

class SiteModule extends Plutonium_Module {
	public function initialize() {
		$request =& Plutonium_Request::getInstance();
		$request->set('resource', $this->_resource = 'pages');
		$request->set('action', 'details');
		$request->set('layout', 'details');
	}
	
	public function execute() {
		$document =& Plutonium_Document::getInstance();
		$document->addStyleSheet('modules/site/styles/module.css');
		
		parent::execute();
	}
}
