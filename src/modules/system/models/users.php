<?php

use Plutonium\Application\Model;
use Plutonium\Database\Table;

class UsersModel extends Model {
	public function lookup($username, $password) {
		if ($user = $this->find(['user' => $username])) {
			$user = $user[0];

			if ($user->pass == hash_hmac('md5', $password, $user->salt))
				return $user;
		}

		return $record;
	}

	public function getTable() {
		if (is_null($this->_table))
			$this->_table = Table::getInstance($this->name);

		return $this->_table;
	}

	public function validate(&$data) {
		if (!isset($data['salt']))
			$data['salt'] = md5(uniqid(rand(), true));

		if (isset($data['pass']))
			$data['pass'] = hash_hmac('md5', $pass, $data['salt']);

		return true;
	}
}
