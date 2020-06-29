<?php

use Plutonium\Http\Session;
use Plutonium\Error\ErrorHandler;

class HttpErrorHandler extends ErrorHandler {
	public function handleError($message, $file, $line) {
		error_log($message);

		throw new ErrorException($message, -1, $level, $file, $line);

		return true;
	}

	public function handleWarning($message, $file, $line) {
		$session = new Session();
		$session->set('message', $message);

		error_log($message);

		return true;
	}

	public function handleNotice($message, $file, $line) {
		error_log($message);

		return true;
	}
}
