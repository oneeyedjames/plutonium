<?php

use Plutonium\Application\Router;

class SiteRouter extends Router {
	public function match($path) {
		$vars = array('resource' => 'pages', 'layout' => 'item');

		$slugs = explode(FS, trim($path, FS));

		if (!empty($slugs))
			$vars['slug'] = array_pop($slugs);

		return $vars;
	}
}
