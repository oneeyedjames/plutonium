<?php

class Plutonium_Utility_Date {
	protected $_time;

	public function __construct($time = null) {
		$this->_time = is_null($time) ? time() : strtotime($time);
	}

	public function format($format, $language = null) {
		if (!is_null($language))
			$format = $language->translate($format);

		return strftime($format, $this->_time);
	}
}

?>