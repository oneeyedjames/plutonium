<?php

use Plutonium\Application\Router;

class SetupRouter extends Router {
	public function match($path) {
		$vars = parent::match($path);
		$vars['resource'] = 'setup';

		return $vars;
	}
}
