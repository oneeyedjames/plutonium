<?php

class BlogRouter extends Plutonium_Module_Router {
	public function match($path = null) {
		$this->module->request->def('resource', 'feeds');

		parent::match($path);
	}
}

?>