<?php

class SetupRouter extends Plutonium_Module_Router {
	public function match($path) {
		$vars = parent::match($path);
		$vars['resource'] = 'steps';

		return $vars;
	}
}

?>