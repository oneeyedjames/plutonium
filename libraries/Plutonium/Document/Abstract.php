<?php

abstract class Plutonium_Document_Abstract extends Plutonium_Object {
	protected static $_instance = null;
	
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
	
	protected $_type = null;
	protected $_lang = 'en-US';
	
	protected $_direction = 'ltr';
	protected $_generator = 'Plutonium CMS';
	
	protected $_title   = null;
	protected $_descrip = null;
	
	public function getTitle() {
		return $this->_title;
	}
	
	public function setTitle($title) {
		$this->_title = $title;
	}
	
	abstract public function display();
}

?>