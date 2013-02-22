<?php

abstract class Plutonium_Error_Handler_Abstract {
	public function handle($level, $message) {
		switch ($level) {
			case E_USER_ERROR:
				$this->handleError($message);
			break;
			case E_USER_WARNING:
				$this->handleWarning($message);
			break;
			case E_USER_NOTICE:
				$this->handleNotice($message);
			break;
		}
	}
	
	abstract public function handleError($message);
	
	abstract public function handleWarning($message);
	
	abstract public function handleNotice($message);
}

?>