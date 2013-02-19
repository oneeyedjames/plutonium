<?php

class Plutonium_HTML_Tag {
	protected $_name;
	protected $_attributes;
	protected $_child_tags;
	protected $_self_close;
	
	public function __construct($name, $attributes = array(), $child_tags = array(), $self_close = TRUE) {
		$this->_name = $name;
		$this->_attributes = is_array($attributes) ? $attributes : array();
		$this->_child_tags = is_array($child_tags) ? $child_tags : array();
		$this->_self_close = $self_close;
	}
	
	public function __get($key) {
		switch ($key) {
			case 'name':
				return $this->getName();
			break;
			case 'attributes':
				return $this->getAttributes();
			break;
			default:
				return NULL;
			break;
		}
	}
	
	public function __set($key, $value) {
		switch ($key) {
			case 'name':
				$this->setName($value);
			break;
			case 'attributes':
				$this->setAttributes($values);
			break;
		}
	}
	
	public function __toString() {
		return $this->toString();
	}
	
	public function getName() {
		return $this->_name;
	}
	
	public function setName($name) {
		$this->_name = $name;
	}
	
	public function getAttributes() {
		return $this->_attributes;
	}
	
	public function setAttributes($attributes, $overwrite = TRUE) {
		foreach ($attributes as $key => $value) {
			$this->setAttribute($key, $value, $overwrite);
		}
	}
	
	public function hasAttribute($key) {
		return isset($this->_attributes[$key]);
	}
	
	public function getAttribute($key, $default = NULL) {
		return isset($this->_attributes[$key]) ? $this->_attributes[$key] : $default;
	}
	
	public function setAttribute($key, $value, $overwrite = TRUE) {
		if (!isset($this->_attributes[$key]) || $overwrite) {
			$this->_attributes[$key] = $value;
		}
	}
	
	public function unsetAttribute($key) {
		if (isset($this->_attributes[$key])) {
			unset($this->_attributes[$key]);
		}
	}
	
	public function addChildTag($tag) {
		
	}
	
	public function setSelfClose($self_close) {
		$this->_self_close = $self_close;
	}
	
	public function toString() {
		$html = '<' . strtolower($this->_name);
		
		foreach ($this->_attributes as $key => $value)
			$html .= ' ' . $key . '="' . $value . '"';
		
		$inner_html = "";
		
		foreach ($this->_child_tags as $tag)
			$inner_html .= $tag->toString();
		
		if (empty($inner_html) && $this->_self_close) {
			$html .= ' />';
		} else {
			$html .= '>' . $this->_inner_html
			      .  '</' . strtolower($this->_name) . '>';
		}
		
		return $html;
	}
}

?>