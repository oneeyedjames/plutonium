<?php

class Plutonium_Router_Path extends Plutonium_Router_Abstract {
	public function parse() {
		$uri['root'] = dirname($_SERVER['SCRIPT_NAME']);
		$uri['self'] = $_SERVER['PHP_SELF'];
		$uri['file'] = $_SERVER['SCRIPT_NAME'];
		$uri += parse_url($_SERVER['REQUEST_URI']);
		
		if ($uri['self'] != $uri['path'] && $uri['self'] == $uri['file']) {
			$path = trim(str_replace(dirname($uri['file']), '', $uri['path']), '/');
		}
		
		if ($uri['self'] == $uri['path'] && $uri['self'] != $uri['file']) {
			$path = trim(str_replace($uri['file'], '', $uri['path']), '/');
		}
		
		if (isset($path) && !empty($path)) {
			$request =& Plutonium_Request::getInstance();
			
			$args = explode('/', $path);
			
			if (isset($args[0])) $request->set('module',   $args[0]);
			if (isset($args[1])) $request->set('resource', $args[1]);
			if (isset($args[2])) $request->set('action',   $args[2]);
			if (isset($args[3])) $request->set('id',       $args[3]);
		}
	}
}

?>