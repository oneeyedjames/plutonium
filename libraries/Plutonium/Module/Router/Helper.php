<?php

class Plutonium_Module_Router_Helper {
	public static function getRouter($driver, $config) {
		$class  = 'Plutonium_Model_Router_' . $driver;
		
		return new $class($config);
	}
}

?>