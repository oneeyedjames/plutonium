<?php

abstract class Plutonium_Module_Abstract {
	protected $_name   = null;
	protected $_params = null;
	protected $_output = null;
	
	protected $_resource = null;
	protected $_action   = null;
	
	public function __construct($name, $params = null) {
		$this->_name = $name;
		$this->_params = is_a($params, 'Plutonium_Object') ? $params
					   : new Plutonium_Object($params);
		
		$this->_resource = 'default';
		$this->_action   = 'default';
	}
	
	public function execute() {
		$controller =& $this->getController();
		$controller->execute();
		$controller->redirect();
	}
	
	public function display() {
		$view =& $this->getView();
		
		$this->_output = $view->display();
		
		return $this->_output;
	}
	
	public function &getController() {
		$request  =& Plutonium_Request::getInstance();
		$resource =  strtolower($request->get('resource', $this->_resource));
		
		return Plutonium_Module_Helper::getController($resource);
	}
	
	public function &getModel() {
		$request  =& Plutonium_Request::getInstance();
		$resource =  strtolower($request->get('resource', $this->_resource));
		
		return Plutonium_Module_Helper::getModel($resource);
	}
	
	public function &getView() {
		$request  =& Plutonium_Request::getInstance();
		$resource =  strtolower($request->get('resource', $this->_resource));
		
		return Plutonium_Module_Helper::getView($resource);
	}
}

?>