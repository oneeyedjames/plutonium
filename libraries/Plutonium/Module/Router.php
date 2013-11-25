<?php

class Plutonium_Module_Router {
	protected $_module = null;

	public function __construct() {
		$this->_module =& Plutonium_Module::getInstance();
	}

	public function match($path = null) {
		$request =& Plutonium_Request::getInstance();

		if (is_null($path))
			$path = $request->get('path', '');

		$path = trim($path, FS);
		$path = empty($path) ? array() : explode(FS, $path);

		if (isset($path[0]))
			$request->set('resource', $path[0]);

		if (isset($path[1])) {
			if (is_numeric($path[1])) {
				$request->set('id', intval($path[1]));

				if (isset($path[2]))
					$request->set('layout', $path[2]);
				else
					$request->def('layout', 'details');
			} else {
				$request->set('layout', $path[1]);
			}
		} else {
			$request->def('layout', 'default');
		}
	}

	public function build($args = null) {
		if (is_null($args))
			$args =& Plutonium_Request::getInstance();

		$path = '';

		if (isset($args->resource)) {
			$path = $args->resource;

			if (isset($args->id))
				$path .= FS . $args->id;

			if (isset($args->action))
				$path .= FS . $args->action;
			elseif (isset($args->layout))
				$path .= FS . $args->layout;
		}

		return $path;
	}
}

?>