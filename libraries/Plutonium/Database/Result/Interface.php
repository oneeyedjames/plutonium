<?php

interface Plutonium_Database_Result_Interface {
	public function reset();
	public function close();
	public function seek($num);
	
	public function getNumFields();
	public function getNumRows();
	
	public function fetch($type = 'array');
	public function fetchArray();
	public function fetchAssoc();
	public function fetchObject();
	public function fetchAll($type = 'array');
	public function fetchAllArray();
	public function fetchAllAssoc();
	public function fetchAllObject();
	public function fetchResult($row = 0, $field = 0);
}

?>