<?php

use Plutonium\Application\Router;

class SiteRouter extends Router {
	public function match($path) {
		$vars = array('resource' => 'pages');
		$path = trim($path, FS);

		if (!empty($path)) {
			$slugs = explode(FS, $path);
			$slug = array_pop($slugs);

			if ($slug == 'form') {
				$vars['layout'] = 'form';
				$slug = array_pop($slugs);
			}

			$vars['slug'] = $slug;
		} else {
			$vars['layout'] = 'item';
		}

		return $vars;
	}
}
