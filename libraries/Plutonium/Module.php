<?php

class Plutonium_Module {
	protected static $_path = null;

	protected static $_default_resource = null;

	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/modules');

		return self::$_path;
	}

	public static function newInstance($application, $name) {
		$name = strtolower($name);
		$type = ucfirst($name) . 'Module';
		$file = self::getPath() . DS . $name . DS . 'module.php';
		$args = new Plutonium_Object(array(
			'application' => $application,
			'name'        => $name
		));

		return Plutonium_Loader::getClass($file, $type, __CLASS__, $args);
	}

	protected $_application = null;

	protected $_name     = null;
	protected $_resource = null;
	protected $_output   = null;

	protected $_router = null;

	protected $_controller = null;
	protected $_models     = array();
	protected $_view       = null;

	public function __construct($args) {
		$this->_application = $args->application;
		$this->_application->language->load($args->name, 'modules');

		$this->_name = $args->name;
	}

	public function __get($key) {
		switch ($key) {
			case 'name':
				return $this->_name;
			case 'path':
				return self::getPath() . DS . strtolower($this->_name);
			case 'resource':
				return $this->_resource;
			case 'request':
				return $this->_application->request;
			case 'application':
				return $this->_application;
		}
	}

	public function initialize() {
		switch ($this->request->method) {
			case 'POST':
				$this->request->set('action', 'create');
				break;
			case 'PUT':
				$this->request->set('action', 'update');
				break;
			case 'DELETE':
				$this->request->set('action', 'delete');
				break;
		}

		$vars = $this->getRouter()->match($this->request->path);

		foreach ($vars as $key => $value)
			$this->request->set($key, $value);

		$this->request->def('resource', self::$_default_resource);

		$this->_resource = $this->request->resource;
	}

	public function execute() {
		$this->getController()->execute();
	}

	public function display() {
		return $this->_output = $this->getView()->display();
	}

	public function getRouter() {
		if (is_null($this->_router)) {
			$type = ucfirst($this->_name) . 'Router';
			$file = self::getPath() . DS . $this->_name . DS . 'router.php';

			$this->_router = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_Router', $this);
		}

		return $this->_router;
	}

	public function getController() {
		if (is_null($this->_controller)) {
			$name = strtolower($this->_resource);
			$type = ucfirst($name) . 'Controller';
			$file = self::getPath() . DS . $this->_name
				  . DS . 'controllers' . DS . $name . '.php';

			$args = new Plutonium_Object(array(
				'module' => $this,
				'name'   => $name
			));

			$this->_controller = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_Controller', $args);
		}

		return $this->_controller;
	}

	public function getModel($name = null) {
		$name = strtolower(is_null($name) ? $this->_resource : $name);

		if (empty($this->_models[$name])) {
			$type = ucfirst($name) . 'Model';
			$file = self::getPath() . DS . $this->_name
				  . DS . 'models' . DS . $name . '.php';

			$args = new Plutonium_Object(array(
				'module' => $this,
				'name'   => $name
			));

			$this->_models[$name] = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_Model', $args);
		}

		return $this->_models[$name];
	}

	public function getView() {
		if (is_null($this->_view)) {
			$name = strtolower($this->_resource);
			$type = ucfirst($name) . 'View';
			$file = self::getPath() . DS . $this->_name . DS
				  . 'views' . DS . $name . DS . 'view.php';

			$args = new Plutonium_Object(array(
				'module' => $this,
				'name'   => $name
			));

			$this->_view = Plutonium_Loader::getClass($file, $type, 'Plutonium_Module_View', $args);
		}

		return $this->_view;
	}

	public function getPermalink() {
		$request = $this->_application->request;
		$config  = $this->_application->config->system;

		$host = $request->module . '.' . $request->host . '.' . $config->hostname;
		$path = $this->getRouter()->build($request);

		if (!empty($path))
			$path .= '.' . $request->get('format', 'html');

		$link = $config->scheme . '://' . $host . '/' . $path;

		return $link;
	}
}
