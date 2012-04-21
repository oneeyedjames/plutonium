<?php

class Plutonium_Language_Helper {
	protected static $_language = NULL;
	
	public static function getLanguage($config = NULL) {
		if (is_null(self::$_language) && !is_null($config)) {
			self::$_language = new Plutonium_Language_Default($config);
		}
		
		return self::$_language;
	}
}

?>