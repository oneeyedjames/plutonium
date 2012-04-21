<?php

class Plutonium_Plugin_Dispatcher extends Plutonium_Object {
	protected $_listeners = array();
	
	public function register($listener) {
		if (is_a($listener, 'Plutonium_Plugin_Listener')) {
			$this->_listeners[] = $listener;
		}
	}
	
	public function trigger($event, &$args = NULL) {
		if (is_null($args)) $args = array();
		
		foreach ($this->_listeners as $listener) {
			$listener->handle($event, $args);
		}
	}
}

?>