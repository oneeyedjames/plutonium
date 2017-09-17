<?php

class Plutonium_Widget extends Plutonium_Component {
	protected static $_path = null;

	public static function getPath() {
		if (is_null(self::$_path) && defined('PU_PATH_BASE'))
			self::$_path = realpath(PU_PATH_BASE . '/widgets');

		return self::$_path;
	}

	public static function setPath($path) {
		self::$_path = $path;
	}

	public static function getMetadata($name) {
		$name = strtolower($name);
		$file = self::getPath() . DS . $name . DS . 'widget.xml';
		$meta = new Plutonium_Object(array(
			'slug' => $name
		));

		if (is_file($file)) {
			$doc = new DOMDocument();
			$doc->preserveWhiteSpace = true;
			$doc->formatOutput = true;
			$doc->load($file);

			$xpath = new DOMXPath($doc);

			$widget_name = $xpath->query('/widget/name')->item(0);

			$meta->name = $widget_name->textContent;

			$description = $xpath->query('/widget/description')->item(0);

			$meta->descrip = $description->textContent;
		} else {
			$meta->name = ucfirst($name);
			$meta->descrip = '';
		}

		return $meta;
	}

	public static function newInstance($application, $name) {
		$name = strtolower($name);
		$file = self::getPath() . DS . $name . DS . 'widget.php';
		$type = ucfirst($name) . 'Widget';
		$args = new Plutonium_Object(array(
			'application' => $application,
			'name' => $name
		));

		return Plutonium_Loader::getClass($file, $type, __CLASS__, $args);
	}

	protected $_vars = null;

	protected $_layout = null;
	protected $_format = null;
	protected $_params = null;
	protected $_output = null;

	public function __construct($args) {
		parent::__construct('widget', $args);

		$this->_vars   = array();
		$this->_layout = 'default';
		$this->_format = 'html';
		$this->_params = $args->params instanceof Plutonium_Object ? $args->params
					   : new Plutonium_Object($args->params);
	}

	public function __get($key) {
		switch ($key) {
			case 'application':
			case 'name':
				return parent::__get($key);
			default:
				return $this->getVar($key);
		}
	}

	public function __set($key, $value) {
		$this->setVal($key, $value);
	}

	public function install() {
		// TODO method stub
	}

	public function display() {
		$request = $this->application->request;

		$name   = strtolower($this->name);
		$layout = strtolower($this->_layout);
		$format = strtolower($request->get('format', $this->_format));

		$file = self::getPath() . DS . $name . DS
		      . 'layouts' . DS . $layout . '.' . $format . '.php';

		if (is_file($file)) {
			ob_start();

			include $file;

			$this->_output = ob_get_contents();

			ob_end_clean();
		} else {
			$message = sprintf("Resource does not exist: %s.", $file);
			trigger_error($message, E_USER_ERROR);
		}

		return $this->_output;
	}

	public function getVar($key) {
		return $this->_vars[$key];
	}

	public function setVal($key, $var) {
		$this->_vars[$key] = $var;
	}

	public function setRef($key, &$var) {
		$this->_vars[$key] = $var;
	}
}
