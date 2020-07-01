<?php

use Plutonium\Application\Document;

use Plutonium\Parser\ThemeParser;
use Plutonium\Parser\UtilityParser;
use Plutonium\Parser\LocaleParser;

use Plutonium\Html\Tag;

class HtmlDocument extends Document {
	protected $_meta_descrip = null;
	protected $_meta_keyword = null;

	protected $_style_sheets = [];
	protected $_script_files = [];

	protected $_parsers = [];

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
		$tags = [];

		$tags[] = new Tag('base', ['href' => PU_URL_BASE . FS]);

		$attribs = ['name' => 'description', 'content' => $this->_meta_descrip];
		$tags[] = new Tag('meta', $attribs);

		$attribs = ['name' => 'keywords', 'content' => $this->_meta_keyword];
		$tags[] = new Tag('meta', $attribs);

		foreach ($this->_style_sheets as $file) {
			$attribs = [
				'rel'  => 'stylesheet',
				'type' => 'text/css',
				'href' => $file
			];

			$tags[] = new Tag('link', $attribs);
		}

		foreach ($this->_script_files as $file) {
			$attribs = [
				'type' => 'text/javascript',
				'src' => $file
			];

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

	public function localize($text) {
		return $text;
	}

	public function sefurl($match) {
		$parts = parse_url($match[0]);

		if (isset($parts['query'])) parse_str($parts['query'], $parts['query']);

		if (in_array(@$parts['path'], [PU_URL_ROOT . 'index.php', 'index.php', null])) {
			$url = PU_URL_BASE;

			$keys = ['module', 'resource', 'action', 'id'];

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
