<?php

use Plutonium\AccessObject;
use Plutonium\Application\Router;
use Plutonium\Database\Table;

class HttpRouter extends Router {
	private $_base_host;

	public function __construct($config) {
		parent::__construct($config);
		$this->_base_host = $config->hostname;
	}

	public function match($host) {
		$vars = new AccessObject();
		$base = $this->_base_host;

		if (substr($host, -strlen($base)) == $base) {
			$diff = explode('.', trim(substr($host, 0, -strlen($base)), '.'));
			$slug = array_pop($diff);

			if ($slug && $host_record = $this->_findHost($slug)) {
				$vars->host = $host_record->slug;
				$slug = array_pop($diff);
			}

			if ($slug && $module_record = $this->_findModule($slug))
				$vars->module = $module_record->slug;
		} elseif ($domain_record = $this->_findDomain($host)) {
			if ($host_record = $this->_findHost($domain_record->host_id))
				$vars->host = $host_record->slug;

			$base = $domain_record->domain;
			$diff = explode('.', trim(substr($host, 0, -strlen($base)), '.'));
			$slug = array_pop($diff);

			if ($slug && $module_record = $this->_findModule($slug))
				$vars->module = $module_record->slug;
			elseif ($module_record = $this->_findModule($domain_record->module_id))
				$vars->module = $module_record->slug;
		}

		return $vars;
	}

	protected function _findHost($slug) {
		$table = Table::getInstance('hosts');

		if (is_numeric($slug) && $row = $table->find(intval($slug)))
			return $row;

		if ($rows = $table->find(compact('slug')))
			return $rows[0];

		return false;
	}

	protected function _findModule($slug) {
		$table = Table::getInstance('modules');

		if (is_numeric($slug) && $row = $table->find(intval($slug)))
			return $row;

		if ($rows = $table->find(compact('slug')))
			return $rows[0];

		return false;
	}

	protected function _findDomain($host) {
		$domain = explode('.', $host);
		$args = ['domain' => []];

		while (count($domain) > 1) {
			$args['domain'][] = implode('.', $domain);
			array_shift($domain);
		}

		if ($rows = Table::getInstance('domains')->find($args))
			return $rows[0];

		return false;
	}
}
