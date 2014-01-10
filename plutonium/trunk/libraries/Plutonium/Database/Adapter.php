<?php

abstract class Plutonium_Database_Adapter {
	protected $_config = null;

	protected $_connection = null;

	public function __construct($config) {
		$this->_config = $config;

		if (!$this->connect())
			trigger_error("Unable to connect to database.", E_USER_ERROR);
	}

	public function __get($key) {
		switch ($key) {
			case 'driver':
				return $this->_config->driver;
		}
	}

	abstract public function connect();
	abstract public function close();

	abstract public function query($sql);

	abstract public function getAffectedRows();
	abstract public function getInsertId();
	abstract public function getErrorNum();
	abstract public function getErrorMsg();

	abstract public function escapeString($str);
	abstract public function quoteString($str);
	abstract public function quoteSymbol($sym);
	abstract public function stripString($str);
	abstract public function stripSymbol($sym);
}

?>