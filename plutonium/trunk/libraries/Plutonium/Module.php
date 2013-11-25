<?php

class Plutonium_Module {
	protected static $_path = null;

	protected static $_instance = null;

	protected static $_default_resource = null;

	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/modules');

		return self::$_path;
	}

	public static function getName() {
		return is_null(self::$_instance) ? null : self::$_instance->name;
	}

	public static function &getInstance($name = null) {
		if (is_null(self::$_instance) && !is_null($name)) {
			$name = strtolower($name);
			$type = ucfirst($name) . 'Module';
			$file = self::getPath() . DS . $name . DS . 'module.php';

			self::$_instance = Plutonium_Loader::getClass($file, $type, __CLASS__, $name);

			$language =& Plutonium_Language::getInstance();
			$language->load($name, 'modules');
		}

		return self::$_instance;
	}

	protected $_name     = null;
	protected $_resource = null;
	protected $_output   = null;

	protected $_router = null;

	protected $_controller = null;
	protected $_models     = array();
	protected $_view       = null;

	public function __construct($name) {
		$this->_name = $name;
	}

	public function __get($key) {
		switch ($key) {
			case 'name':
				return $this->_name;
			case 'path':
				return self::$_path . DS . strtolower($this->_name);
			case 'resource':
				return $this->_resource;
		}
	}

	public function initialize() {
		$request =& Plutonium_Request::getInstance();

		switch ($request->method) {
			case 'POST':
				$request->def('action', 'create');
				break;
			case 'PUT':
				$request->def('action', 'update');
				break;
			case 'DELETE':
				$request->def('action', 'delete');
				break;
		}

		$this->getRouter()->match($request->path);
		$this->_resource = $request->get('resource', self::$_default_resource);

		$request->def('resource', self::$_default_resource);
	}

	public function execute() {
		$this->getController()->execute();
	}

	public function display() {
		return $this->_output = $this->getView()->display();
	}

	public function &getRouter() {
		if (is_null($this->_router)) {
			$type = ucfirst($this->_name) . 'Router';
			$file = self::getPath() . DS . $this->_name . DS . 'router.php';

			$this->_router = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_Router');
		}

		return $this->_router;
	}

	public function &getController() {
		if (is_null($this->_controller)) {
			$name = strtolower($this->_resource);
			$type = ucfirst($name) . 'Controller';
			$file = self::getPath() . DS . $this->_name
				  . DS . 'controllers' . DS . $name . '.php';

			$this->_controller = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_Controller', $name);
		}

		return $this->_controller;
	}

	public function &getModel($name = null) {
		$name = strtolower(is_null($name) ? $this->_resource : $name);

		if (empty($this->_models[$name])) {
			$type = ucfirst($name) . 'Model';
			$file = self::getPath() . DS . $this->_name
				  . DS . 'models' . DS . $name . '.php';

			$this->_models[$name] = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_Model', $name);
		}

		return $this->_models[$name];
	}

	public function &getView() {
		if (is_null($this->_view)) {
			$name = strtolower($this->_resource);
			$type = ucfirst($name) . 'View';
			$file = self::getPath() . DS . $this->_name . DS
				  . 'views' . DS . $name . DS . 'view.php';

			$this->_view = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_View', $name);
		}

		return $this->_view;
	}

	public function getPermalink() {
		$request  =& Plutonium_Request::getInstance();
		$registry =& Plutonium_Registry::getInstance();

		$scheme = $registry->config->system->scheme;

		$host = $request->module . '.' . $request->host . '.'
			  . $registry->config->system->hostname;

		$path = $this->getRouter()->build();

		if (!empty($path))
			$path .= '.' . $request->get('format', 'html');

		$link = $scheme . '://' . $host . FS . $path;

		return $link;
	}
}

?>