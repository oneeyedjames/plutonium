<?php

class UsersController extends Plutonium_Module_Controller_Abstract {
	public function loginAction() {
		global $registry;
		
		$session = Plutonium_Session::getInstance();
		$request = Plutonium_Request::getInstance();
		
		$data = $request->get('data');
		
		if (!empty($data)) {
			$model = $this->getModel();
			
			$user = $model->lookup($data['username'], $data['password']);
			
			if ($user) {
				$session->set('user', $user);
				
				$this->setRedirect($request->get('return', P_PATH_URL));
			} else {
				Plutonium_Error_Helper::triggerWarning('Invalid Username/Password');
			}
		}
	}
	
	public function logoutAction() {
		$request = Plutonium_Request::getInstance();
		$session = Plutonium_Session::getInstance();
		$session->del('user');
		
		$this->setRedirect($request->get('return', P_PATH_URL));
	}
}
