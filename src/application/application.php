<?php

use Plutonium\Loader;
use Plutonium\Application\Application;
use Plutonium\Database\Table;

class HttpApplication extends Application {
	protected static $_instance = null;

	protected $_name = 'http';
	protected $_router = null;

	public function __get($key) {
		switch ($key) {
			case 'name':
				return $this->_name;
			case 'router':
				return $this->_getRouter();
			default:
				return parent::__get($key);
		}
	}

	public function initialize() {
		$default_host   = $this->config->system->default_host;
		$default_theme  = $this->config->application->default_theme;
		$default_module = $this->config->application->default_module;

		$route = $this->router->match($this->request->get('Host', null, 'headers'));

		$this->request->host   = $route->get('host',   $default_host);
		$this->request->module = $route->get('module', $default_module);

		$this->config->def('theme', $default_theme);

		parent::initialize();

		$table = Table::getInstance('modules');
		$rows  = $table->find(['slug' => $this->request->module]);

		if (count($rows)) {
			foreach ($rows[0]->widget as $widget) {
				$xref = $widget->xref->module;
				$this->addWidget($xref->location, $widget->slug);
			}
		}
	}

	protected function _getRouter() {
		if (is_null($this->_router)) {
			$type = ucfirst(strtolower($this->name)) . 'Router';
			$file = realpath(PU_PATH_BASE . '/application/router.php');
			$this->_router = Loader::getClass($file, $type,
				'Plutonium\Application\Router', $this->config->system);
		}

		return $this->_router;
	}
}
