<?php

class Plutonium_Module_Controller {
	protected $_name     = null;
	protected $_module   = null;
	protected $_redirect = null;
	
	public function __construct($name, $module) {
		$this->_name   = $name;
		$this->_module = $module;
	}
	
	public function setRedirect($url) {
		$this->_redirect = $url;
	}
	
	public function &getModel($name = null) {
		return $this->_module->getModel($name);
	}
	
	public function &getView() {
		return $this->_module->getView();
	}
	
	public function execute() {
		$request =& Plutonium_Request::getInstance();
		
		$action = strtolower($request->get('action', 'default'));
		$method = $action . 'Action';
		
		if (method_exists($this, $method))
			call_user_func(array($this, $method));
		
		if (!empty($this->_redirect))
			header('Location: ' . $this->_redirect);
	}
}

?>