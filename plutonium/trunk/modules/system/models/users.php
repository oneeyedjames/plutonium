<?php

class UsersModel extends Plutonium_Module_Model {
	public function lookup($username, $password) {
		if ($user = $this->find(array('user' => $username))) {
			$user = $user[0];

			if ($user->pass == hash_hmac('md5', $password, $user->salt))
				return $user;
		}

		/* $db = $registry->database;

		$table = $db->quoteSymbol('sys_users');

		$username_field = $db->quoteSymbol('username');
		$username_value = $db->quoteString($username);

		$password_field = $db->quoteSymbol('password');
		$password_value = $db->quoteString($password);

		$sql = "SELECT * FROM $table "
			 . "WHERE $username_field = $username_value "
			 . "AND $password_field = $password_value";

		$result = $db->query($sql);
		$record = $result->fetchObject();
		$result->close(); */

		return $record;
	}
}
