<?php

abstract class Plutonium_Filter_Abstract {
	public function filter($value, $type) {
		if ($callback = $this->canHandle($type))
			return call_user_func($callback, $value);

		return $value;
	}

	public function canHandle($type) {
		$method = strtolower($type) . 'Filter';

		if (method_exists($this, $method))
			return array($this, $method);

		return false;
	}
}

?>