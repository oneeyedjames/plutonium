<?php

class SiteModule extends Plutonium_Module_Abstract {
	public function execute() {
		$this->_resource = 'pages';
		
		$document =& Plutonium_Document::getInstance();
		$document->addStyleSheet('modules/site/styles/module.css');
		
		parent::execute();
	}
}
