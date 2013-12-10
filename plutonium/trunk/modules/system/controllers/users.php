<?php

class UsersController extends Plutonium_Module_Controller {
	public function loginAction() {
		global $registry;

		$session = new Plutonium_Session();
		$request = Plutonium_Request::getInstance();

		$data = $request->get('data');

		if (!empty($data)) {
			$model = $this->getModel();

			$user = $model->lookup($data['username'], $data['password']);

			if ($user) {
				$session->set('user', $user);

				$this->redirect = $request->get('return', PU_URL_PATH);
			} else {
				Plutonium_Error_Helper::triggerWarning('Invalid Username/Password');
			}
		}
	}

	public function logoutAction() {
		$request = Plutonium_Request::getInstance();
		$session = new Plutonium_Session();
		$session->del('user');

		$this->redirect = $request->get('return', PU_URL_PATH);
	}
}
