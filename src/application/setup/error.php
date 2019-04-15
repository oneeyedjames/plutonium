<?php

use Plutonium\Error\AbstractHandler;

class SetupErrorHandler extends AbstractHandler {
	public function handleError($message) {
		die($message);
	}

	public function handleWarning($message) {
		return true;
	}

	public function handleNotice($message) {
		return true;
	}
}
