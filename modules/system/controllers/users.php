<?php

use Plutonium\Application\Controller;
use Plutonium\Http\Session;

class UsersController extends Controller {
	public function loginAction() {
		$request = $this->module->request;
		$session = new Session();

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
		$session = new Session();
		$session->del('user');

		$this->redirect = $request->get('return', PU_URL_PATH);
	}
}
