<?php

abstract class Plutonium_Database_Adapter {
	protected $_config = null;

	public function __construct($config) {
		$this->_config = $config;
		$this->connect();
	}

	public function __get($key) {
		switch ($key) {
			case 'driver':
				return $this->_config->driver;
		}
	}

	abstract public function connect();
	abstract public function close();

	abstract public function getAffectedRows();
	abstract public function getInsertId();
	abstract public function getErrorNum();
	abstract public function getErrorMsg();

	abstract public function escapeString($str);
	abstract public function quoteString($str);
	abstract public function quoteSymbol($sym);
	abstract public function stripString($str);
	abstract public function stripSymbol($sym);
	abstract public function query($sql, $limit = 0, $offset = 0);
}

?>