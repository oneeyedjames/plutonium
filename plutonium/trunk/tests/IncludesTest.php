<?php

class IncludesTest extends PHPUnit_Framework_TestCase {
	private $_path;
	private $_host = 'plutonium.dev';

	public function setUp() {
		$this->_path = dirname(dirname(__FILE__));

		$_SERVER['DOCUMENT_ROOT'] = $this->_path;
		$_SERVER['SCRIPT_FILENAME'] = $this->_path . '/index.php';
		$_SERVER['SERVER_NAME'] = $this->_host;

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI']    = '/';

		require_once $this->_path . '/constants.php';
	}

	public function testConstants() {
		$this->assertNotEmpty(DS);
		$this->assertNotEmpty(PS);
		$this->assertNotEmpty(LS);
		$this->assertNotEmpty(FS);
		$this->assertNotEmpty(BS);

		$this->assertNotEmpty(PU_PATH_BASE);
		$this->assertNotEmpty(PU_PATH_ROOT);
		$this->assertNotEmpty(PU_URL_ROOT);
		$this->assertNotEmpty(PU_URL_BASE);

		$this->assertTrue(defined('PU_URL_PATH'));
	}
}

?>
