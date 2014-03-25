<?php

class Plutonium_Parser_Utility extends Plutonium_Parser_Abstract {
	public function __construct($application, $config) {
		parent::__construct($application);
		date_default_timezone_set($config->timezone);
	}

	public function dateTag($args) {
		$time   = isset($args['time']) ? strtotime($args['time']) : time();
		$format = isset($args['format']) ? $args['format'] : 'date_format_long';

		$regex = '/^(date|time|datetime)_format_(long|short|system)$/';

		if (preg_match($regex, $format))
			$format = $this->_application->language->translate($format);

		return strftime($format, $time);
	}
}
