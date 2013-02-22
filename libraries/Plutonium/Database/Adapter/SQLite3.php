<?php

class Plutonium_Database_Adapter_SQLite3 extends Plutonium_Database_Adapter_Abstract {
	protected $_connection = null;
	protected $_result = null;
	
	public function connect() {
		$this->_connection = new SQLite3($this->_config->dbfile);
		
		return is_object($this->_connection);
	}
	
	public function close() {
		return $this->_connection->close();
	}
	
	public function getAffectedRows() {
		// raise error
		
		return -1;
	}
	
	public function getInsertId() {
		return $this->_connection->lastInsertRowID();
	}
	
	public function getErrorNum() {
		return $this->_connection->lastErrorCode();
	}
	
	public function getErrorMsg() {
		return $this->_connection->lastErrorMsg();
	}
	
	public function escapeString(str) {
		return $this->_connection->escapeString($str);
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
		
		$result = $this->_connection->query($sql);
		
		if (is_object($result)) {
			$this->_result = $result;
			
			return new Plutonium_Database_Result_SQLite3($result);
		}
		
		return $result;
	}
}

?>