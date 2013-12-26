<?php

class Plutonium_Filter_String extends Plutonium_Filter_Abstract {
	public function alphaFilter($value) {
		return preg_replace('/[^A-Z]/i', '', $value);
	}

	public function alnumFilter($value) {
		return preg_replace('/[^A-Z0-9]/i', '', $value);
	}

	public function digitFilter($value) {
		return preg_replace('/[^0-9]/', '', $value);
	}

	public function xdigitFilter($value) {
		return preg_replace('/[^A-F0-9]/i', '', $value);
	}

	public function lcaseFilter($value) {
		return preg_replace('/[^a-z]/', '', $value);
	}

	public function ucaseFilter($value) {
		return preg_replace('/[^A-Z]/', '', $value);
	}
}

?>