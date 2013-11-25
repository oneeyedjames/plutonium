<?php

class SiteRouter extends Plutonium_Module_Router {
	public function match($path = null) {
		$request =& Plutonium_Request::getInstance();
		$request->set('resource', 'pages');
		$request->set('layout', 'details');

		$slugs = explode(FS, trim($path, FS));

		if (!empty($slugs))
			$request->set('slug', array_pop($slugs));
	}
}

?>