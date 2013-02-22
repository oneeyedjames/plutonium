<?php

class Plutonium_Router_Default extends Plutonium_Router_Abstract {
	public function parse() {
	}
	
	public function build($args) {
		$url = 'index.php?' . http_build_query($args);
	}
}

?>