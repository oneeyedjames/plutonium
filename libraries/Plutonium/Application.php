<?php

class Plutonium_Application {
	protected static $_path = null;

	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/application');

		return self::$_path;
	}

	protected $_config = null;

	protected $_theme   = null;
	protected $_module  = null;
	protected $_widgets = array();

	protected $_session = null;
	protected $_request = null;

	protected $_response = null;
	protected $_language = null;
	protected $_document = null;

	public function __construct($config) {
		$this->_config = $config;
	}

	public function __get($key) {
		switch ($key) {
			case 'config':
				return $this->_config;
			case 'theme':
				return $this->_getTheme($this->_config);
			case 'module':
				return $this->_getModule($this->request);
			case 'widgets':
				return $this->_widgets;
			case 'session':
				return $this->_getSession();
			case 'request':
				return $this->_getRequest($this->_config);
			case 'response':
				return $this->_getResponse();
			case 'language':
				return $this->_getLanguage($this->_config);
			case 'document':
				return $this->_getDocument($this->_config, $this->request);
		}
	}

	protected function _getTheme($config) {
		if (is_null($this->_theme) && !is_null($config))
			$this->_theme = Plutonium_Theme::newInstance($this, $config->theme);

		return $this->_theme;
	}

	protected function _getModule($request) {
		if (is_null($this->_module) && !is_null($request))
			$this->_module = Plutonium_Module::newInstance($this, $request->module);

		return $this->_module;
	}

	protected function _getSession() {
		if (is_null($this->_session))
			$this->_session = new Plutonium_Session();

		return $this->_session;
	}

	protected function _getRequest($config) {
		if (is_null($this->_request) && !is_null($config))
			$this->_request = new Plutonium_Request($config->system);

		return $this->_request;
	}

	protected function _getResponse() {
		if (is_null($this->_response))
			$this->_response = new Plutonium_Response();

		return $this->_response;
	}

	protected function _getLanguage($config) {
		if (is_null($this->_language) && !is_null($config))
			$this->_language = new Plutonium_Language($config->language);

		return $this->_language;
	}

	protected function _getDocument($config, $request) {
		if (is_null($this->_document) && !is_null($config)) {
			$format = !is_null($request) ? $request->get('format', 'html') : 'html';

			$args = new Plutonium_Object(array(
				'application' => $this,
				'location'    => $config->location
			));

			$this->_document = Plutonium_Document::newInstance($format, $args);
		}

		return $this->_document;
	}

	public function initialize() {
		$this->module->initialize();
	}

	public function execute() {
		$this->module->execute();

		$this->response->setModuleOutput($this->module->display());

		foreach ($this->widgets as $location => $widgets) {
			foreach ($widgets as $position => $widget) {
				$this->response->setWidgetOutput($location, $widget->display());
			}
		}

		$this->response->setThemeOutput($this->theme->display());

		$this->document->display();
	}

	public function addWidget($location, $name) {
		$this->_widgets[$location][] = Plutonium_Widget::newInstance($this, $name);
	}
}

?>