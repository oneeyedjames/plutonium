<?php

class Plutonium_Utility_Date {
	protected $_time;
	
	public function __construct($time = null) {
		$this->_time = is_null($time) ? time() : strtotime($time);
	}
	
	public function format($format, $translate = true) {
		if ($translate) {
			$language = Plutonium_Language::getInstance();
			$format   = $language->translate($format);
		}
		
		return strftime($format, $this->_time);
	}
}

?>