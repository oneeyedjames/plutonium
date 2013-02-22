<?php

abstract class Plutonium_Observable extends Plutonium_Object {
	protected $_observers = array();
	
	public function attach(&$observer) {
		if (!in_array($observer, $this->_observers)) {
			$this->_observers[] = $observer;
		}
	}
	
	public function detach(&$observer) {
		if (($index = array_search($observer, $this->_observers)) !== false) {
			unset($this->_observers[$index]);
		}
	}
	
	public function notify() {
		foreach ($this->_observers as $observer) {
			$observer->notify();
		}
	}
	
	public abstract function getState();
	
	public abstract function setState();
}

?>