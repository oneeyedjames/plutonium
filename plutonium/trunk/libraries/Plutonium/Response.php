<?php

class Plutonium_Response {
	protected static $_instance = null;
	
	public static function &getInstance() {
		if (is_null(self::$_instance))
			self::$_instance = new self();
		
		return self::$_instance;
	}
	
	protected $_theme_output  = null;
	protected $_module_output = null;
	protected $_widget_output = array();
	
	protected function __construct() {
	}
	
	public function getThemeOutput() {
		return $this->_theme_output;
	}
	
	public function setThemeOutput($output) {
		$this->_theme_output = $output;
	}
	
	public function getModuleOutput() {
		$theme =& Plutonium_Theme::getInstance();
		
		return $theme->module_start
			 . $this->_module_output
			 . $theme->module_close;
	}
	
	public function setModuleOutput($output) {
		$this->_module_output = $output;
	}
	
	public function getWidgetCount($location) {
		return (int) @count($this->_widget_output[$location]);
	}
	
	public function getWidgetOutput($location) {
		if (isset($this->_widget_output[$location])) {
			$theme =& Plutonium_Theme::getInstance();
			
			$outputs = array();
			
			foreach (array_keys($this->_widget_output[$location]) as $position) {
				$outputs[] = $theme->widget_start
						   . $this->_widget_output[$location][$position]
						   . $theme->widget_close;
			}
			
			return implode($theme->widget_delim, $outputs);
		}
		
		return null;
	}
	
	public function setWidgetOutput($location, $output) {
		$this->_widget_output[$location][] = $output;
	}
}

?>