<?php

abstract class Plutonium_Database_Adapter_Abstract
implements Plutonium_Database_Adapter_Interface {
	protected $_config = null;
	
	public function __construct($config) {
		$this->_config = $config;
		$this->connect();
	}
	
	public function __get($key) {
		switch ($key) {
			case 'driver':
				return $this->_config->driver;
				break;
			default:
				return null;
				break;
		}
	}
}

?>