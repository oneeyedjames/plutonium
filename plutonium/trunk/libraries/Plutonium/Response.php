<?php

class Plutonium_Response {
	protected static $_instance = NULL;
	
	public static function &getInstance() {
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	protected $_theme_output  = NULL;
	protected $_module_output = NULL;
	protected $_widget_output = array();
	
	protected $_module_start = '<div class="p_module">';
	protected $_module_close = '</div>';
	
	protected $_widget_start = '<div class="p_widget">';
	protected $_widget_close = '</div>';
	protected $_widget_delim = LS;
	
	protected function __construct() {
	}
	
	public function getThemeOutput() {
		return $this->_theme_output;
	}
	
	public function setThemeOutput($output) {
		$this->_theme_output = $output;
	}
	
	public function getModuleOutput() {
		return $this->_module_start
			 . $this->_module_output
			 . $this->_module_close;
	}
	
	public function setModuleOutput($output) {
		$this->_module_output = $output;
	}
	
	public function getWidgetCount($location) {
		return (int) @count($this->_widget_output[$location]);
	}
	
	public function getWidgetOutput($location) {
		if (isset($this->_widget_output[$location])) {
			$outputs =  array();
			
			foreach (array_keys($this->_widget_output[$location]) as $position) {
				$outputs[] = $this->_widget_start
						   . $this->_widget_output[$location][$position]
						   . $this->_widget_close;
			}
			
			return implode($this->_widget_delim, $outputs);
		}
		
		return NULL;
	}
	
	public function setWidgetOutput($location, $output) {
		$this->_widget_output[$location][] = $output;
	}
}

?>