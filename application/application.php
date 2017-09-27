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
		$route = $this->router->match($this->request->get('Host', null, 'headers'));
		// $route = $this->router->match('blog.pu');

		if (isset($route->host))
			$this->request->host = $route->host;

		if (isset($route->module))
			$this->request->host = $route->module;

		// Lookup default host
		if (!isset($this->request->host)) {
			$table = Table::getInstance('hosts');
			if ($rows = $table->find(array('default' => 1)))
				$this->request->host = $rows[0]->slug;
		}

		// Lookup default module
		if (!isset($this->request->module)) {
			$table = Table::getInstance('modules');
			if ($rows = $table->find(array('default' => 1)))
				$this->request->module = $rows[0]->slug;
		}

		// Lookup default theme
		if (!isset($this->config->theme)) {
			$table = Table::getInstance('themes');
			if ($rows = $table->find(array('default' => 1)))
				$this->config->theme = $rows[0]->slug;
		}

		// Go for broke with hard-coded defaults
		$this->request->def('host',   'main');
		$this->request->def('module', 'site');

		$this->config->def('theme', 'charcoal');

		parent::initialize();

		// Load widgets
		$table = Table::getInstance('modules');
		if ($rows = $table->find(array('slug' => $this->request->module))) {
			$widgets = array();

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

			$this->_router = Loader::getClass($file, $type, 'Plutonium\Application\Router', $this->config->system);
		}

		return $this->_router;
	}
}
