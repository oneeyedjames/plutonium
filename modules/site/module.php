<?php

class SiteModule extends Plutonium_Module {
	public function execute() {
		$document =& Plutonium_Document::getInstance();
		$document->addStyleSheet('modules/site/styles/module.css');

		parent::execute();
	}
}
