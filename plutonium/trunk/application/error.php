<?php

class HttpErrorHandler extends Plutonium_Error_Handler {
	public function handleError($message) {
		die($message);
	}

	public function handleWarning($message) {
		$session = new Plutonium_Session();
		$session->set('message', $message);

		return true;
	}

	public function handleNotice($message) {
		$session = new Plutonium_Session();
		$session->set('message', $message);

		return true;
	}
}
