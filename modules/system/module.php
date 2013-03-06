<?php

class SystemModule extends Plutonium_Module {
	public function execute() {
		$this->_resource = 'users';
		
		parent::execute();
	}
}
