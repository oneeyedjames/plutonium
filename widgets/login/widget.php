<?php

class LoginWidget extends Plutonium_Widget_Abstract {
	public function display() {
		$request =& Plutonium_Request::getInstance();
		$session =& Plutonium_Session::getInstance();
		
		if ($user = $session->get('user')) {
			$this->_layout = 'logout';
			
			$this->setVal('user', $user);
		} else {
			$this->_layout = 'login';
			
			$this->setVal('url',  $request->uri);
		}
		
		return parent::display();
	}
}
