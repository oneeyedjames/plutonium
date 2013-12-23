<?php

class RequestTest extends PHPUnit_Framework_TestCase {
	var $config;

	public function setUp() {
		$_SERVER['DOCUMENT_ROOT'] = PU_PATH_BASE;
		$_SERVER['SCRIPT_FILENAME'] = PU_PATH_BASE . '/index.php';

		$_SERVER['SERVER_NAME'] = 'plutonium.dev';

		$config['system']['hostname'] = 'plutonium.dev';

		if (is_null($this->config))
			$this->config = new Plutonium_Object($config);
	}

	public function testMethod() {
		$_SERVER['REQUEST_METHOD'] = 'GET';

		$request = new Plutonium_Request($this->config->system);

		$this->assertEquals($request->method, 'GET');

		$_SERVER['REQUEST_METHOD'] = 'POST';

		$request = new Plutonium_Request($this->config->system);

		$this->assertEquals($request->method, 'POST');

		$_REQUEST['_method'] = 'PUT';

		$request = new Plutonium_Request($this->config->system);

		$this->assertEquals($request->method, 'PUT');
	}

	public function testHost() {
		$_SERVER['SERVER_NAME'] = 'main.plutonium.dev';

		$request = new Plutonium_Request($this->config->system);

		$this->assertEquals($request->host, 'main');
		$this->assertNull($request->module);

		$_SERVER['SERVER_NAME'] = 'site.main.plutonium.dev';

		$request = new Plutonium_Request($this->config->system);

		$this->assertEquals($request->host,   'main');
		$this->assertEquals($request->module, 'site');
	}

	public function testPath() {
		$_SERVER['REQUEST_URI'] = '/path/to/resource.ext';

		$request = new Plutonium_Request($this->config->system);

		$this->assertEquals($request->path, 'path/to/resource');
		$this->assertEquals($request->format, 'ext');

		$_SERVER['REQUEST_URI'] = '/path/to/resource';

		$request = new Plutonium_Request($this->config->system);

		$this->assertEquals($request->path, 'path/to/resource');
		$this->assertNull($request->format);
	}
}

?>