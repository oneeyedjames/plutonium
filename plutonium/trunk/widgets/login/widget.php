<?php

class LoginWidget extends Plutonium_Widget {
	public function display() {
		$session = $this->_application->session;
		$request = $this->_application->request;

		if ($user = $session->get('user')) {
			$this->_layout = 'logout';

			$this->setVal('user', $user);
		} else {
			$this->_layout = 'login';

			$this->setVal('url',  $this->_application->request->uri);
		}

		return parent::display();
	}
}
