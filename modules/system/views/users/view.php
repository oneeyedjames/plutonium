<?php

use Plutonium\Application\View;

class UsersView extends View {
	public function render() {
		$this->layout = 'login';

		return parent::render();
	}
}
