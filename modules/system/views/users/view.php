<?php

use Plutonium\Application\View;

class UsersView extends View {
	public function display() {
		$this->_layout = 'login';

		return parent::display();
	}
}
