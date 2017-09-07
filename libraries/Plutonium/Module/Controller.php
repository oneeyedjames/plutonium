<?php

class Plutonium_Module_Controller {
	protected $_name     = null;
	protected $_module   = null;
	protected $_redirect = null;

	public function __construct($args) {
		$this->_name   = $args->name;
		$this->_module = $args->module;
	}

	public function __get($key) {
		switch ($key) {
			case 'name':
				return $this->_name;
			case 'request':
				return $this->_module->request;
			case 'redirect':
				return $this->_redirect;
		}
	}

	public function __set($key, $value) {
		switch ($key) {
			case 'redirect':
				$this->_redirect = $value;
				break;
		}
	}

	public function getModel($name = null) {
		return $this->_module->getModel($name);
	}

	public function getView() {
		return $this->_module->getView();
	}

	public function execute() {
		$request = $this->_module->request;

		$action = strtolower($request->get('action', 'default'));
		$method = $action . 'Action';

		if (method_exists($this, $method))
			call_user_func(array($this, $method));

		if (!empty($this->_redirect))
			header('Location: ' . $this->_redirect);
	}
}
