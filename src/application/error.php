<?php

use Plutonium\Http\Session;
use Plutonium\Error\AbstractHandler;

class HttpErrorHandler extends AbstractHandler {
	public function handleError($message) {
		error_log($message);
		die($message);
	}

	public function handleWarning($message) {
		$session = new Session();
		$session->set('message', $message);

		error_log($message);

		return true;
	}

	public function handleNotice($message) {
		error_log($message);

		return true;
	}
}
