<?php

class Plutonium_Filter_String extends Plutonium_Filter_Abstract {
	public static function filter($value, $type) {
		switch (strtolower($type)) {
			case 'alpha':
				return self::filterAlpha($value);
			break;
			case 'alnum':
				return self::filterAlnum($value);
			break;
			case 'digit':
				return self::filterDigit($value);
			break;
			case 'lower':
				return self::filterLower($value);
			break;
			case 'upper':
				return self::filterUpper($value);
			break;
			case 'xdigit':
				return self::filterXDigit($value);
			break;
			default:
				return $value;
			break;
		}
	}
	
	public static function filterAlpha($value) {
		return preg_replace('/[^A-Z]/i', '', $value);
	}
	
	public static function filterAlnum($value) {
		return preg_replace('/[^A-Z0-9]/i', '', $value);
	}
	
	public static function filterDigit($value) {
		return preg_replace('/[^0-9]/', '', $value);
	}
	
	public static function filterLower($value) {
		return preg_replace('/[^a-z]/', '', $value);
	}
	
	public static function filterUpper($value) {
		return preg_replace('/[^A-Z]/', '', $value);
	}
	
	public static function filterXDigit($value) {
		return preg_replace('/[^A-F0-9]/i', '', $value);
	}
}

?>