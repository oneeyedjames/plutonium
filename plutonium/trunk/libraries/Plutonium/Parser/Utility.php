<?php

class Plutonium_Parser_Utility extends Plutonium_Parser_Abstract {
	protected $_namespace = 'p';
	
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