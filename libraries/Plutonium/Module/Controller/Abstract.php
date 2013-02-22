<?php

abstract class Plutonium_Module_Controller_Abstract {
	protected $_name     = null;
	protected $_redirect = null;
	
	public function __construct($name) {
		$this->_name = $name;
	}
	
	public function execute() {
		$request =& Plutonium_Request::getInstance();
		
		$action = strtolower($request->get('action', 'default'));
		$method = $action . 'Action';
		
		if (method_exists($this, $method)) {
			call_user_func(array($this, $method));
		}
	}
	
	public function redirect() {
		if (!empty($this->_redirect)) {
			header('Location: ' . $this->_redirect);
		}
	}
	
	public function setRedirect($url) {
		$this->_redirect = $url;
	}
	
	public function &getModel() {
		return Plutonium_Module_Helper::getModel($this->_name);
	}
	
	public function &getView() {
		return Plutonium_Module_Helper::getView($this->_name);
	}
}

?>