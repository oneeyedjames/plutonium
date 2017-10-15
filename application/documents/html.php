<?php

use Plutonium\Application\Document;

use Plutonium\Parser\ThemeParser;
use Plutonium\Parser\UtilityParser;
use Plutonium\Parser\LocaleParser;

use Plutonium\Html\Tag;

class HtmlDocument extends Document {
	protected $_meta_descrip = null;
	protected $_meta_keyword = null;

	protected $_style_sheets = array();
	protected $_script_files = array();

	protected $_parsers = array();

	public function __construct($args) {
		parent::__construct($args);

		$this->_parsers[] = new ThemeParser($this->application);
		$this->_parsers[] = new UtilityParser($this->application, $args);
		$this->_parsers[] = new LocaleParser($this->application);
	}

	public function addStyleSheet($path) {
		$this->_style_sheets[] = $path;
	}

	public function addScriptFile($path) {
		$this->_script_files[] = $path;
	}

	public function getHeader() {
		$tags = array();

		$tags[] = new Tag('base', array('href' => PU_URL_BASE . FS));

		$attribs = array('name' => 'description', 'content' => $this->_meta_descrip);
		$tags[] = new Tag('meta', $attribs);

		$attribs = array('name' => 'keywords', 'content' => $this->_meta_keyword);
		$tags[] = new Tag('meta', $attribs);

		foreach ($this->_style_sheets as $file) {
			$attribs = array(
				'rel'  => 'stylesheet',
				'type' => 'text/css',
				'href' => $file
			);

			$tags[] = new Tag('link', $attribs);
		}

		foreach ($this->_script_files as $file) {
			$attribs = array(
				'type' => 'text/javascript',
				'src' => $file
			);

			$tags[] = new Tag('script', $attribs, null, false);
		}

		if (!empty($this->_title)) {
			$tags[] = new Tag('title', null, $this->_title);
		}

		$html = implode(LS, $tags);

		return $html;
	}

	public function render() {
		$response = $this->_application->response;

		$output = $response->getThemeOutput();

		foreach ($this->_parsers as $parser)
			$output = $parser->parse($output);

		return $output;
	}

	public function sefurl($match) {
		$parts = parse_url($match[0]);

		if (isset($parts['query'])) parse_str($parts['query'], $parts['query']);

		if (in_array(@$parts['path'], array(PU_URL_ROOT . 'index.php', 'index.php', null))) {
			$url = PU_URL_BASE;

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
