<?php

use Plutonium\Application\View;

class UsersView extends View {
	public function render() {
		$this->_layout = 'login';

		return parent::render();
	}
}
