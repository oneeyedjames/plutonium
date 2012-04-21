<?php

class Plutonium_Utility_Address {
	public static function build($ip) {
		$octets = explode('.', $ip);
		
		if (count($octets) != 4) return NULL;
		
		$int = 0;
		
		for ($i = 0; $i < 4; $i++) {
			$int += $octets[$i] * pow(0x100, $i);
		}
		
		return $int;
	}
	
	public static function parse($int) {
		$octets = array();
		
		for ($i = 0; $i < 4; $i++) {
			$bitmask = 0xFF * pow(0x100, $i);
			$octets[$i] = ($int & $bitmask) / pow(256, $i);
		}
		
		$ip = implode('.', $octets);
		
		return $ip;
	}
}

?>