<?php

class Plutonium_Database_Adapter_PostgeSQL extends Plutonium_Database_Adapter {
	protected $_connection = null;
	protected $_result = null;

	public function connect() {
		$host = $this->_config->hostname;
		$port = $this->_config->port;
		$user = $this->_config->username;
		$pass = $this->_config->password;
		$db   = $this->_config->dbname;

		$conn_str = "host='" . $host . "' "
		          . "port='" . $port . "' "
		          . "user='" . $user . "' "
				  . "password='" . $pass . "' "
				  . "dbname='" . $db . "' ";

		$this->_connection = pg_connect($conn_str);

		return is_resource($this->_connection);
	}

	public function close() {
		return pg_close($this->_connection);
	}

	public function getAffectedRows() {
		return pg_affected_rows($this->_result);
	}

	public function getInsertId() {
		return pg_last_oid($this->_result);
	}

	public function getErrorNum() {
		return 0;
	}

	public function getErrorMsg() {
		return pg_last_error($this->_connection);
	}

	public function escapeString($str) {
		return pg_escape_string($this->_connection, $str);
	}

	public function quoteString($str) {
		return "'" . $this->escapeString($str) . "'";
	}

	public function quoteSymbol($sym) {
		return '"' . $sym . '"';
	}

	public function query($sql, $limit = 0, $offset = 0) {
		if ((int) $limit > 0) $sql .= ' LIMIT ' . (int) $limit;
		if ((int) $offset > 0) $sql .= ' OFFSET ' . (int) $offset;

		$result = pg_query($this->_connection, $sql);

		if (is_resource($result)) {
			$this->_result = $result;

			return new Plutonium_Database_Result_PostgreSQL($result);
		}

		return $result;
	}
}

?>