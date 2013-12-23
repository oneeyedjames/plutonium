<?php

class ResponseTest extends PHPUnit_Framework_TestCase {
	public function testModule() {
		$args = new Plutonium_Object(array(
			'module_start' => '<article>',
			'module_close' => '</article>'
		));

		$response = new Plutonium_Response();
		$response->setModuleOutput('Hello, World!');

		$expected = '<article>Hello, World!</article>';

		$this->assertEquals($expected, $response->getModuleOutput($args));
	}

	public function testWidget() {
		$args = new Plutonium_Object(array(
			'widget_start' => '<aside>',
			'widget_delim' => '<hr>',
			'widget_close' => '</aside>'
		));

		$response = new Plutonium_Response();
		$response->setWidgetOutput('sidebar', '1');
		$response->setWidgetOutput('sidebar', '2');
		$response->setWidgetOutput('footer', '3');

		$expected = '<aside>1</aside><hr><aside>2</aside>';
		$actual = $response->getWidgetOutput('sidebar', $args);

		$this->assertEquals($expected, $actual);

		$expected = '<aside>3</aside>';
		$actual = $response->getWidgetOutput('footer', $args);

		$this->assertEquals($expected, $actual);
	}
}

?>