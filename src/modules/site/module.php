<?php

/**
 * A typical content management package
 * @package Plutonium Site Module
 */

use Plutonium\Application\Module;

class SiteModule extends Module {
	public function execute() {
		$document = $this->_application->document;
		$document->addStyleSheet('modules/site/styles/module.css');

		parent::execute();
	}
}
