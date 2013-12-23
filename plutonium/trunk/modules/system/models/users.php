<?php

class UsersModel extends Plutonium_Module_Model {
	public function lookup($username, $password) {
		if ($user = $this->find(array('user' => $username))) {
			$user = $user[0];

			if ($user->pass == hash_hmac('md5', $password, $user->salt))
				return $user;
		}

		return $record;
	}
}
