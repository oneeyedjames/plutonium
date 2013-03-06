<?php

class Plutonium_Module {
	protected static $_name = null;
	protected static $_path = null;
	
	protected static $_instance = null;
	
	public static function getName() {
		return self::$_name;
	}
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/modules');
		
		return self::$_path;
	}
	
	public static function &getInstance($name = null) {
		self::$_name = $name = strtolower($name);
		
		if (is_null(self::$_instance) && !is_null($name)) {
			$type = ucfirst($name) . 'Module';
			$file = self::getPath() . DS . $name . DS . 'module.php';
			
			self::$_instance = Plutonium_Loader::getClass($file, $type, __CLASS__);
			
			$language =& Plutonium_Language::getInstance();
			$language->load(self::getPath() . DS . $name . DS . 'languages');
		}
		
		return self::$_instance;
	}
	
	protected $_resource = null;
	protected $_output   = null;
	
	protected $_controller = null;
	protected $_models     = array();
	protected $_view       = null;
	
	public function __construct() {
		$this->_resource = 'default';
	}
	
	public function initialize() {
		$request =& Plutonium_Request::getInstance();
		
		$path = $request->get('path', null);
		
		if (!empty($path)) {
			$request->set('resource', $this->_resource = $path[0]);
			
			if (isset($path[1])) {
				if (is_numeric($path[1])) {
					$request->set('id', intval($path[1]));
					
					if (isset($path[2])) {
						$request->set('action', $path[2]);
						$request->set('layout', $path[2]);
					}
				} else {
					$request->set('action', $path[1]);
					$request->set('layout', $path[1]);
				}
			}
		}
	}
	
	public function execute() {
		$this->getController()->execute();
	}
	
	public function display() {
		return $this->_output = $this->getView()->display();
	}
	
	public function &getRouter() {
		if (is_null($this->_router)) {
			$type = ucfirst(self::$_name) . 'Router';
			$file = self::getPath() . DS . self::$_name . DS . 'router.php';
			
			$this->_router = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_Router');
		}
		
		return $this->_router;
	}
	
	public function &getController() {
		if (is_null($this->_controller)) {
			$request  =& Plutonium_Request::getInstance();
			
			$name = strtolower($request->get('resource', $this->_resource));
			$type = ucfirst($name) . 'Controller';
			$file = self::getPath() . DS . self::$_name
				  . DS . 'controllers' . DS . $name . '.php';
			
			$this->_controller = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_Controller', $name);
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
			
			$this->_models[$name] = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_Model', $name);
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
			
			$this->_view = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_View', $name);
		}
		
		return $this->_view;
	}
}

?>