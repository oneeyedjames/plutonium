<?php
interface Plutonium_Database_Adapter_Interface {
	public function connect();
	public function close();
	
	public function getAffectedRows();
	public function getInsertId();
	public function getErrorNum();
	public function getErrorMsg();
	
	public function escapeString($str);
	public function quoteString($str);
	public function quoteSymbol($sym);
	public function stripString($str);
	public function stripSymbol($sym);
	public function query($sql, $limit = 0, $offset = 0);
}

?>