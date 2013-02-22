<?php

class Plutonium_Utility_date {
	protected $_time;
	
	public function __construct($time = null) {
		$this->_time = is_null($time) ? time() : strtotime($time);
	}
	
	public function format($format, $translate = true) {
		if ($translate) {
			$language = Plutonium_Language_Helper::getLanguage();
			$format   = $language->translate($format);
		}
		
		return strftime($format, $this->_time);
	}
}

?>