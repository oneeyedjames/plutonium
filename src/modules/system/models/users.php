<?php

use Plutonium\Application\Model;
use Plutonium\Database\Table;

class UsersModel extends Model {
	public function lookup($username, $password) {
		if ($user = $this->find(['username' => $username])) {
			$user = $user[0];

			if (password_verify($password, $user->password)) {
				if (password_needs_rehash($user->password, PASSWORD_DEFAULT)) {
					$user->password = password_hash($user->password, PASSWORD_DEFAULT);
					$this->save($user->toArray());
				}

				unset($user->password);

				return $user;
			}
		}

		return false;
	}

	public function getTable() {
		if (is_null($this->_table))
			$this->_table = Table::getInstance($this->name);

		return $this->_table;
	}

	public function validate(&$data) {
		if (isset($data['password']))
			$data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

		return true;
	}
}
