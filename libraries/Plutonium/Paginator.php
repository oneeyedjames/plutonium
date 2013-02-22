<?php

class Plutonium_Paginator {
	protected $_index = 0;
	protected $_limit = 0;
	protected $_total = 0;
	
	public function __construct($index, $limit, $total) {
		$this->index = $index;
		$this->limit = $limit;
		$this->total = $total;
	}
	
	public function getRowIndex() {
		return $this->_index;
	}
	
	public function getRowLimit() {
		return $this->_limit;
	}
	
	public function getRowTotal() {
		return $this->_total;
	}
	
	public function getPageIndex() {
		return $this->_limit == 0 ? 0 : floor($this->_index / $this->_limit);
	}
	
	public function getPageTotal() {
		return $this->_limit == 0 ? 0 : ceil($this->_total / $this->_limit);
	}
}

?>