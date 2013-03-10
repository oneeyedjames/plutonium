<?php

class Plutonium_Application {
	protected static $_path = null;
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/application');
		
		return self::$_path;
	}
	
	protected $_models = array();
	
	protected $_theme   = null;
	protected $_module  = null;
	protected $_widgets = array();
	
	public function initialize() {
		$request  =& Plutonium_Request::getInstance();
		$registry =& Plutonium_Registry::getInstance();
		
		$this->_theme  =& Plutonium_Theme::getInstance($registry->config->get('theme'));
		$this->_module =& Plutonium_Module::getInstance($request->get('module'));
		
		$registry->config->def('widgets', array());
		
		foreach ($registry->config->get('widgets') as $location => $widgets) {
			foreach ($widgets as $position => $widget) {
				$this->_widgets[$location][$position] = Plutonium_Widget::getInstance($widget);
			}
		}
		
		$this->_module->initialize();
	}
	
	public function execute() {
		$this->_module->execute();
		
		$response =& Plutonium_Response::getInstance();
		$response->setModuleOutput($this->_module->display());
		
		foreach ($this->_widgets as $location => $widgets) {
			foreach ($widgets as $position => $widget) {
				$response->setWidgetOutput($location, $widget->display());
			}
		}
		
		$response->setThemeOutput($this->_theme->display());
		
		$document =& Plutonium_Document::getInstance();
		$document->display();
	}
}

?>