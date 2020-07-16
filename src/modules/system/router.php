<?php

use Plutonium\AccessObject;
use Plutonium\Application\Router;

class SystemRouter extends Router {
	public function match($path) {
		$vars = parent::match($path);
		$vars->def('resource', 'modules');

		return $vars;
	}

	public function build($args) {
		if (is_array($args)) $args = new AccessObject($args);

		$path = parent::build($args);

		// if (isset($args->action)) $path .= FS . $args->action;

		return $path;
	}
}
