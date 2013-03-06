<?php

class Plutonium_Parser_Utility extends Plutonium_Parser_Abstract {
	protected $_namespace = 'pu';
	
	public function __construct() {
		date_default_timezone_set('America/New_York');
	}
	
	public function dateTag($args) {
		$time   = isset($args['time']) ? strtotime($args['time']) : time();
		$format = isset($args['format']) ? $args['format'] : '%d %B %Y';
		
		return strftime($format, $time);
	}
	
	public function timeTag($args) {
		$time   = isset($args['time']) ? strtotime($args['time']) : time();
		$format = isset($args['format']) ? $args['format'] : '%H:%M';
		
		return strftime($format, $time);
	}
}

?>