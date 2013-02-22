<?php

class Plutonium_Plugin_Listener {
	public function handle($event, &$args) {
		$method = 'handle' . ucfirst($event);
		
		if (method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $args);
		} else {
			return null;
		}
	}
}

?>