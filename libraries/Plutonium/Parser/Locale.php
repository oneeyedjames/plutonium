<?php

class Plutonium_Parser_Locale extends Plutonium_Parser_Abstract {
	public function transTag($args) {
		return $this->_application->locale->translate($args['phrase']);
	}
}
