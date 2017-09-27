<?php

use Plutonium\Application\Router;

class BlogRouter extends Router {
	public function match($path = null) {
		$this->module->request->def('resource', 'feeds');

		parent::match($path);
	}
}
