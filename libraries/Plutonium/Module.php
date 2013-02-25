<?php

class Plutonium_Module {
	protected static $_name = null;
	protected static $_path = null;
	
	protected static $_instance = null;
	
	public static function getName() {
		return self::$_name;
	}
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('P_BASE_PATH'))
			self::$_path = realpath(P_BASE_PATH . '/modules');
		
		return self::$_path;
	}
	
	public static function setPath($path) {
		self::$_path = $path;
	}
	
	public static function &getInstance($name) {
		$name = strtolower($name);
		self::$_name = $name;
		
		if (is_null(self::$_instance)) {
			$type = ucfirst($name) . 'Module';
			$file = self::getPath() . DS . $name . DS . 'module.php';
			
			if (is_file($file)) require_once $file;
			
			$type = class_exists($type) ? $type : __CLASS__;
			
			self::$_instance = new $type($name);
			
			$language =& Plutonium_Language::getInstance();
			$language->load(self::getPath() . DS . $name . DS . 'languages');
		}
		
		return self::$_instance;
	}
	
	protected $_params = null;
	protected $_output = null;
	
	protected $_resource = null;
	protected $_action   = null;
	
	protected static $_controller = null;
	protected static $_models     = array();
	protected static $_view       = null;
	
	public function __construct($params = null) {
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
		if (is_null($this->_controller)) {
			$request  =& Plutonium_Request::getInstance();
			
			$name = strtolower($request->get('resource', $this->_resource));
			$type = ucfirst($resource) . 'Controller';
			$file = self::getPath() . DS . self::$_name
				  . DS . 'controllers' . DS . $name . '.php';
			
			if (is_file($file)) require_once $file;
			
			$type = class_exists($type) ? $type
				  : 'Plutonium_Module_Controller_Default';
			
			$this->_controller = new $type($name);
		}
		
		return $this->_controller;
	}
	
	public function &getModel($name = null) {
		if (is_null($name))
			$name = Plutonium_Request::getInstance()->get('resource', $this->_resource);
		
		$name = strtolower($name);
		
		if (empty($this->_models[$name])) {
			$type = ucfirst($name) . 'Model';
			$file = self::getPath() . DS . self::$_name
				  . DS . 'models' . DS . $name . '.php';
			
			if (is_file($file)) require_once $file;
			
			$type = class_exists($type) ? $type
				  : 'Plutonium_Module_Model_Default';
			
			$this->_models[$name] = new $type($name);
		}
		
		return $this->_models[$name];
	}
	
	public function &getView() {
		if (is_null($this->_view)) {
			$request =& Plutonium_Request::getInstance();
			
			$name = strtolower($request->get('resource', $this->_resource));
			$type = ucfirst($name) . 'View';
			$file = self::getPath() . DS . self::$_name . DS
				  . 'views' . DS . $name . DS . 'view.php';
			
			if (is_file($file)) require_once $file;
			
			$type = class_exists($type) ? $type
				  : 'Plutonium_Module_View_Default';
			
			$this->_view = new $type($name);
		}
		
		return $this->_view;
	}
}

?>