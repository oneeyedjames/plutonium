<?php

class BlogRouter extends Plutonium_Module_Router {
	public function match($path = null) {
		$request =& Plutonium_Request::getInstance();
		$request->def('resource', 'feeds');

		parent::match($path);
	}
}

?>