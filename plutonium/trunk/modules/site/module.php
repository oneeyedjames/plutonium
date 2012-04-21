<?php

class SiteModule extends Plutonium_Module_Abstract {
	public function execute() {
		$this->_resource = 'pages';
		
		$document =& Plutonium_Document_Helper::getDocument();
		$document->addStyleSheet('modules/site/styles/module.css');
		
		parent::execute();
	}
}
