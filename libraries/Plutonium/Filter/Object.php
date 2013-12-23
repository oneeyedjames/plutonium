<?php

class Plutonium_Filter_Object extends Plutonium_Filter_Abstract {
	public static function filter($value, $type) {
		switch (strtolower($type)) {
			case 'bool':
			case 'boolean':
				return self::filterBool($value);
			case 'int':
			case 'integer':
				return self::filterInt($value);
			case 'float':
			case 'double':
				return self::filterFloat($value);
			case 'string':
				return self::filterString($value);
			case 'array':
				return self::filterArray($value);
			case 'object':
				return self::filterObject($value);
			default:
				return $value;
		}
	}

	public static function filterBool($value) {
		return is_scalar($value) ? (bool) $value : null;
	}

	public static function filterInt($value) {
		return is_scalar($value) ? (int) $value : null;
	}

	public static function filterFloat($value) {
		return is_scalar($value) ? (float) $value : null;
	}

	public static function filterString($value) {
		return is_scalar($value) ? (string) $value : null;
	}

	public static function filterArray($value) {
		if (is_array($value))
			return $value;
		elseif (is_object($value))
			return (array) $value;
		else
			return null;
	}

	public static function filterObject($value) {
		return is_array($value) || is_object($value) ? (object) $value : null;
	}
}

?>