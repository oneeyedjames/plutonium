<?php

class RequestTest extends PHPUnit_Framework_TestCase {
	var $config;

	public function setUp() {
		$config['system']['hostname'] = 'plutonium.dev';

		if (is_null($this->config))
			$this->config = new Plutonium_Object($config);
	}

	public function tearDown() {
		$_SERVER['SERVER_NAME'] = 'plutonium.dev';

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI']    = '/';
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
