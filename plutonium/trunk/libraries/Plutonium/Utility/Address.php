<?php

class Plutonium_Utility_Address {
	public static function newInstance($ip) {
		$octets = array();

		if (is_int($ip))
			$octets = self::parseInt($ip);
		elseif (is_string($ip))
			$octets = self::parseString($ip);

		return new self($octets);
	}

	public static function parseInt($int) {
		$octets = array();

		for ($i = 0, $n = 4; $i < $n; $i++) {
			$j = $n - $i - 1;
			$mask = 0xFF * pow(0x100, $j);
			$octets[$i] = ($int & $mask) / pow(0x100, $j);
		}

		return $octets;
	}

	public static function parseString($str) {
		$octets = array_slice(explode('.', $str), -4);

		foreach ($octets as &$octet)
			$octet = intval($octet) & 0xFF;

		while (count($octets) < 4)
			array_unshift($octets, 0x00);

		return $octets;
	}

	protected $_octets = array();

	public function __construct($octets = array()) {
		$this->_octets = $octets;
	}

	public function toInt() {
		$int = 0;

		for ($i = 0, $n = 4; $i < $n; $i++) {
			$j = $n - $i - 1;
			$int += $this->_octets[$i] * pow(0x100, $j);
		}

		return $int;
	}

	public function toString() {
		return implode('.', $this->_octets);
	}
}

?>