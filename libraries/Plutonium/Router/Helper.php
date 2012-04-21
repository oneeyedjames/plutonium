<?php

class Plutonium_Router_Helper {
	public static function getRouter($name) {
		$type = 'Plutonium_Router_' . $name;
		
		return new $type();
	}
}

?>