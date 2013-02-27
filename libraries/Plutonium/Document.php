<?php

class Plutonium_Document extends Plutonium_Object {
	protected static $_instance = null;
	
	public static function &getInstance() {
		if (is_null(self::$_instance)) {
			$request =& Plutonium_Request::getInstance();
			
			$format = $request->get('format', 'html');
			
			$name = strtolower($format);
			$type = ucfirst($name) . 'Document';
			
			$path = Plutonium_Application::getPath() . '/documents';
			$file = $path . DS . $name . '.php';
			
			self::$_instance = Plutonium_Loader::getClass($file, $type, __CLASS__);
		}
		
		return self::$_instance;
	}
	
	protected $_type = null;
	protected $_lang = 'en-US';
	
	protected $_title   = null;
	protected $_descrip = null;
	
	public function getTitle() {
		return $this->_title;
	}
	
	public function setTitle($title) {
		$this->_title = $title;
	}
	
	public function display() {
		echo $this->_descrip;
	}
}

?>