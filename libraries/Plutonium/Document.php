<?php

class Plutonium_Document extends Plutonium_Object {
	public static function newInstance($format, $args) {
		$name = strtolower($format);
		$type = ucfirst($name) . 'Document';

		$path = Plutonium_Application::getPath() . '/documents';
		$file = $path . DS . $name . '.php';

		return Plutonium_Loader::getClass($file, $type, __CLASS__, $args);
	}

	protected $_type = null;
	protected $_lang = 'en-US';

	protected $_title   = null;
	protected $_descrip = null;

	public function getTitle() {
		return $this->_title;
	}

	public function setTitle($title) {
		$this->_title = $title;
	}

	public function display() {
		echo $this->_descrip;
	}
}

?>