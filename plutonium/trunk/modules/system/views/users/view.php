<?php

class UsersView extends Plutonium_Module_View_Abstract {
	public function display() {
		$this->_layout = 'login';
		
		return parent::display();
	}
}
