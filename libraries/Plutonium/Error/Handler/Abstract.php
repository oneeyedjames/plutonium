<?php

abstract class Plutonium_Error_Handler_Abstract {
	public function handle($level, $message) {
		switch ($level) {
			case E_USER_ERROR:
				return $this->handleError($message);
			case E_USER_WARNING:
				return $this->handleWarning($message);
			case E_USER_NOTICE:
				return $this->handleNotice($message);
		}
	}

	abstract public function handleError($message);

	abstract public function handleWarning($message);

	abstract public function handleNotice($message);
}

?>