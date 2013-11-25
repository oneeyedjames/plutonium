<?php

class Plutonium_Filter_String extends Plutonium_Filter_Abstract {
	public static function filter($value, $type) {
		switch (strtolower($type)) {
			case 'alpha':
				return self::filterAlpha($value);
			case 'alnum':
				return self::filterAlnum($value);
			case 'digit':
				return self::filterDigit($value);
			case 'lower':
				return self::filterLower($value);
			case 'upper':
				return self::filterUpper($value);
			case 'xdigit':
				return self::filterXDigit($value);
			default:
				return $value;
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