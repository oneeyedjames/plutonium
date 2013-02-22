<?php

class Plutonium_Application {
	protected static $_path = null;
	
	public static function getPath() {
		if (is_null(self::$_path) && defined('P_BASE_PATH'))
			self::$_path = realpath(P_BASE_PATH . '/application');
		
		return self::$_path;
	}
	
	public static function setPath($path) {
		self::$_path = $path;
	}
	
	protected $_theme   = null;
	protected $_module  = null;
	protected $_widgets = array();
	
	public function initialize() {
		$request  =& Plutonium_Request::getInstance();
		$registry =& Plutonium_Registry::getInstance();
		
		$this->_theme  = Plutonium_Theme_Helper::getTheme($registry->config->get('theme'));
		$this->_module = Plutonium_Module_Helper::getModule($request->get('module'));
		
		$registry->config->def('widgets',  array());
		
		foreach ($registry->config->get('widgets') as $location => $widgets) {
			foreach ($widgets as $position => $widget) {
				$this->_widgets[$location][$position] = Plutonium_Widget_Helper::getWidget($widget);
			}
		}
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
		
		$document =& Plutonium_Document_Helper::getDocument();
		$document->display();
	}
}

?>