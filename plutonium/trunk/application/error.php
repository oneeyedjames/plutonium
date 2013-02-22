<?php

class HttpErrorHandler extends Plutonium_Error_Handler_Abstract {
	public function handleError($message) {
		die($message);
	}
	
	public function handleWarning($message) {
		$session =& Plutonium_Session::getInstance();
		$session->set('message', $message);
	}
	
	public function handleNotice($message) {
		$session =& Plutonium_Session::getInstance();
		$session->set('message', $message);
	}
}

?>