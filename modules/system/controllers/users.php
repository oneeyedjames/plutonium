<?php

class UsersController extends Plutonium_Module_Controller {
	public function loginAction() {
		global $registry;

		$request = $this->module->request;
		$session = new Plutonium_Session();

		$data = $request->get('data');

		if (!empty($data)) {
			$model = $this->getModel();

			$user = $model->lookup($data['username'], $data['password']);

			if ($user) {
				$session->set('user', $user);

				$this->redirect = $request->get('return', PU_URL_PATH);
			} else {
				trigger_error('Invalid Username/Password', E_USER_WARNING);
			}
		}
	}

	public function logoutAction() {
		$request = $this->module->request;
		$session = new Plutonium_Session();
		$session->del('user');

		$this->redirect = $request->get('return', PU_URL_PATH);
	}
}
