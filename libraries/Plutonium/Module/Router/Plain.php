<?php

class Plutonium_Module_Router_Plain extends Plutonium_Module_Router_Abstract {
	public function build($params) {
		return 'index.php?' . http_build_query($params);
	}
	
	public function parse($url) {
	}
}

?>