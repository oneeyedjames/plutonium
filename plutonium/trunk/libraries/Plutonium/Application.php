<?php

class Plutonium_Application {
	protected static $_path = null;

	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/application');

		return self::$_path;
	}

	protected $_theme   = null;
	protected $_module  = null;
	protected $_widgets = array();

	protected $_session = null;
	protected $_request = null;

	protected $_response = null;
	protected $_language = null;
	protected $_document = null;

	public function __get($key) {
		switch ($key) {
			case 'theme':
			case 'module':
			case 'widgets':
			case 'session':
			case 'request':
			case 'response':
			case 'language':
			case 'document':
				$property = '_' . $key;
				return $this->$property;
		}
	}

	public function initialize($config) {
		$request = $this->getRequest($config);

		//$this->_request = new Plutonium_Request($config);
		$this->_session  = new Plutonium_Session();
		$this->_response = new Plutonium_Response();

		$format = $this->_request->get('format', 'html');

		$this->_language = new Plutonium_Language($config->language);
		$this->_document = Plutonium_Document::newInstance($format, $config);

		$this->_theme = Plutonium_Theme::newInstance($this, $config->theme);

		$this->_module = Plutonium_Module::newInstance($this, $request->module);
		$this->_module->initialize();
	}

	public function execute() {
		$this->_module->execute();

		$this->_response->setModuleOutput($this->_module->display());

		foreach ($this->_widgets as $location => $widgets) {
			foreach ($widgets as $position => $widget) {
				$this->_response->setWidgetOutput($location, $widget->display());
			}
		}

		$this->_response->setThemeOutput($this->_theme->display());

		$this->_document->display();
	}

	// TODO find a better way to initialize the request from child classes
	public function getRequest($config) {
		if (is_null($this->_request))
			$this->_request = new Plutonium_Request($config);

		return $this->_request;
	}

	public function addWidget($location, $name) {
		$this->_widgets[$location][] = Plutonium_Widget::newInstance($this, $name);
	}
}

?>