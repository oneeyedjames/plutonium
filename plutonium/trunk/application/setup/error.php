<?php

class SetupErrorHandler extends Plutonium_Error_Handler_Abstract {
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
