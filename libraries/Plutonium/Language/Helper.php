<?php

class Plutonium_Language_Helper {
	protected static $_language = null;
	
	public static function getLanguage($config = null) {
		if (is_null(self::$_language) && !is_null($config)) {
			self::$_language = new Plutonium_Language_Default($config);
		}
		
		return self::$_language;
	}
}

?>