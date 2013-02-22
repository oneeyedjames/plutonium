<?php

class Plutonium_Event_Dispatcher extends Plutonium_Object {
	protected $_listeners = array();
	
	public function __construct() {
		parent::__construct();
	}
	
	public function register($event, $handler) {
		if (function_exists($handler)) {
			$method = array('event' => $event, 'handler' => $handler);
			$this->_listeners[] = $method;
		} elseif (class_exists($handler)) {
			$this->_listeners[] = new $handler($this);
		} else {
			// raise error: invalid handler type
		}
	}
	
	public function trigger($event, $args = null) {
		if (is_null($args)) $args = array();
		
		foreach ($this->_listeners as $listener) {
			if (is_object($listener)) {
				if (method_exists($listener, 'handle')) {
					$args['event'] = $event;
					$listener->update($args);
				} else {
					// raise error: undefined handler method
				}
			} elseif (is_array($listener)) {
				if ($listener['event'] == $event) {
					if (function_exists($listener['handler'])) {
						call_user_func_array($listener['handler'], $args);
					} else {
						// raise error: undefined handler function
					}
				}
			}
		}
	}
}

?>