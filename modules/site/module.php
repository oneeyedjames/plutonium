<?php

class SiteModule extends Plutonium_Module {
	public function __construct($name) {
		parent::__construct($name);
		self::$_default_resource = 'pages';
	}

	public function execute() {
		$document = $this->_application->document;
		$document->addStyleSheet('modules/site/styles/module.css');

		parent::execute();
	}
}
