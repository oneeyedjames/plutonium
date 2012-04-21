<?php

class Plutonium_Event_Listener {
	protected $_dispatcher = NULL;
	
	public function __construct(&$dispatcher) {
		$this->_dispatcher = $dispatcher;
		$this->_dispatcher->register(__CLASS__);
	}
	
	public function handle(&$args) {
		$method = 'handle' . ucfirst($args['event']);
		unset($args['event']);
		
		if (method_exists($this, $method)) {
			return call_user_func_array (array($this, $method), $args);
		} else {
			return NULL;
		}
	}
}

?>