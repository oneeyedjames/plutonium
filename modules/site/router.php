<?php

class SiteRouter extends Plutonium_Module_Router {
	public function match($path) {
		$vars = array('resource' => 'pages', 'layout' => 'details');

		$slugs = explode(FS, trim($path, FS));

		if (!empty($slugs))
			$vars['slug'] = array_pop($slugs);

		return $vars;
	}
}
