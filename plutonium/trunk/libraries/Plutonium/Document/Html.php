<?php

class Plutonium_Document_Html extends Plutonium_Document_Abstract {
	protected $_meta_descrip = NULL;
	protected $_meta_keyword = NULL;
	
	protected $_style_sheets = array();
	protected $_script_files = array();
	
	protected $_parsers = array();
	
	public function __construct() {
		parent::__construct();
		
		$this->_parsers[] = new Plutonium_Parser_Theme();
		$this->_parsers[] = new Plutonium_Parser_Utility();
	}
		
	public function addStyleSheet($path) {
		$this->_style_sheets[] = $path;
	}
	
	public function addScriptFile($path) {
		$this->_script_files[] = $path;
	}
	
	public function getHeader() {
		$tags = array();
		
		$tags[] = new Plutonium_HTML_Tag('base', array('href' => P_BASE_URL . FS));
		
		$attribs = array('name' => 'description', 'content' => $this->_meta_descrip);
		$tags[] = new Plutonium_HTML_Tag('meta', $attribs);
		
		$attribs = array('name' => 'keywords', 'content' => $this->_meta_keyword);
		$tags[] = new Plutonium_HTML_Tag('meta', $attribs);
		
		foreach ($this->_style_sheets as $file) {
			$attribs = array(
				'rel'  => 'stylesheet',
				'type' => 'text/css',
				'href' => $file
			);
			
			$tags[] = new Plutonium_HTML_Tag('link', $attribs);
		}
		
		foreach ($this->_script_files as $file) {
			$attribs = array(
				'type' => 'text/javascript',
				'src' => $file
			);
			
			$tags[] = new Plutonium_HTML_Tag('script', $attribs, NULL, FALSE);
		}
		
		if (!empty($this->_title)) {
			$tags[] = new Plutonium_HTML_Tag('title', NULL, $this->_title);
		}
		
		$html = implode(LS, $tags);
		
		return $html;
	}
	
	public function display() {
		$response =& Plutonium_Response::getInstance();
		
		$tmpl = $response->getThemeOutput();
		
		foreach ($this->_parsers as $parser) {
			$tmpl = $parser->parse($tmpl);
		}
		
		/*$pattern = '/(' . preg_quote(P_BASE_URL, '/') . '|' . preg_quote(P_ROOT_URL, '/') . ')?'
				 . 'index\.php(\?\w+=\w+(&\w+=\w+)+)?/msi';
		
		$tmpl = preg_replace_callback($pattern, array($this, 'sefurl'), $tmpl);*/
		
		echo $tmpl;
	}
	
	public function sefurl($match) {
		$parts = parse_url($match[0]);
		
		if (isset($parts['query'])) parse_str($parts['query'], $parts['query']);
		//echo '<pre>' . htmlspecialchars(print_r($match, TRUE)) . '</pre>';
		//echo '<pre>' . htmlspecialchars(print_r($parts, TRUE)) . '</pre>';
		
		if (in_array(@$parts['path'], array(P_ROOT_URL . 'index.php', 'index.php', NULL))) {
			$url = P_BASE_URL;
			
			$keys = array('module', 'resource', 'action', 'id');
			
			$i = 0;
			while ($i < count($keys) && !empty($parts['query'][$keys[$i]])) {
				$url .= $parts['query'][$keys[$i]] . FS;
				$i++;
			}
		} else {
			$url = $match['href'];
		}
		
		return $url;
	}
}

?>