<?php

abstract class Plutonium_Application_Abstract {
	protected $_theme   = NULL;
	protected $_module  = NULL;
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