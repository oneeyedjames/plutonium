<?php

class Plutonium_Parser_Language extends Plutonium_Parser_Abstract {
	public function transTag($args) {
		return $this->_application->language->translate($args['phrase']);
	}
}
