<?php

abstract class Plutonium_Document_Abstract extends Plutonium_Object {
	protected static $_instance = NULL;
	
	public static function &getInstance() {
		if (is_null(self::$_instance)) {
			$request =& Plutonium_Request::getInstance();
			
			$format = $request->get('format', 'html');
			
			$name = strtolower($format);
			$type = 'Plutonium_Document_' . ucfirst($name);
			
			self::$_instance = new $type();
		}
		
		return self::$_instance;
	}
	
	protected $_type = NULL;
	protected $_lang = 'en-US';
	
	protected $_direction = 'ltr';
	protected $_generator = 'Plutonium CMS';
	
	protected $_title   = NULL;
	protected $_descrip = NULL;
	
	public function getTitle() {
		return $this->_title;
	}
	
	public function setTitle($title) {
		$this->_title = $title;
	}
	
	abstract public function display();
}

?>