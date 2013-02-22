<?php

class Plutonium_Document_Helper {
	protected static $_document = null;
	
	public static function &getDocument() {
		if (is_null(self::$_document)) {
			$request =& Plutonium_Request::getInstance();
			
			$format = $request->get('format', 'html');
			
			$name = strtolower($format);
			$type = 'Plutonium_Document_' . ucfirst($name);
			
			self::$_document = new $type();
		}
		
		return self::$_document;
	}
}

?>