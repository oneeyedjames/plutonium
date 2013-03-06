<?php

class UsersView extends Plutonium_Module_View {
	public function display() {
		$this->_layout = 'login';
		
		return parent::display();
	}
}
