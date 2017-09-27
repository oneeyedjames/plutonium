<?php

use Plutonium\Http\Session;
use Plutonium\Error\AbstractHandler;

class HttpErrorHandler extends AbstractHandler {
	public function handleError($message) {
		die($message);
	}

	public function handleWarning($message) {
		$session = new Session();
		$session->set('message', $message);

		return true;
	}

	public function handleNotice($message) {
		$session = new Session();
		$session->set('message', $message);

		return true;
	}
}
