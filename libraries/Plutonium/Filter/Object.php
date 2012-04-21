<?php

class Plutonium_Filter_Object extends Plutonium_Filter_Abstract {
	public static function filter($value, $type) {
		switch (strtolower($type)) {
			case 'bool':
			case 'boolean':
				return self::filterBool($value);
			break;
			case 'int':
			case 'integer':
				return self::filterInt($value);
			break;
			case 'float':
			case 'double':
				return self::filterFloat($value);
			break;
			case 'string':
				return self::filterString($value);
			break;
			case 'array':
				return self::filterArray($value);
			break;
			case 'object':
				return self::filterObject($value);
			break;
			default:
				return $value;
			break;
		}
	}
	
	public static function filterBool($value) {
		return (bool) $value;
	}
	
	public static function filterInt($value) {
		return (int) $value;
	}
	
	public static function filterFloat($value) {
		return (float) $value;
	}
	
	public static function filterString($value) {
		return (string) $value;
	}
	
	public static function filterArray($value) {
		return (array) $value;
	}
	
	public static function filterObject($value) {
		return (object) $value;
	}
}

?>