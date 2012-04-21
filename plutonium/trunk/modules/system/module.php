<?php

class SystemModule extends Plutonium_Module_Abstract {
	public function execute() {
		$this->_resource = 'users';
		
		//die('<pre>' . print_r($this, TRUE) . '</pre>');
		parent::execute();
	}
}
