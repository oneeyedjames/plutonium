<?php

class AddressTest extends PHPUnit_Framework_TestCase {
	public function testAddress() {
		$address = new Plutonium_Utility_Address(array(127, 0, 0, 1));

		$this->assertEquals(0x7f000001, $address->toInt());
		$this->assertEquals('127.0.0.1', $address->toString());

		$address = Plutonium_Utility_Address::newInstance('127.0.0.1');

		$this->assertEquals(0x7f000001, $address->toInt());
		$this->assertEquals('127.0.0.1', $address->toString());

		$address = Plutonium_Utility_Address::newInstance(0x7F000001);

		$this->assertEquals(0x7f000001, $address->toInt());
		$this->assertEquals('127.0.0.1', $address->toString());

		$address = Plutonium_Utility_Address::newInstance('127.0.0');

		$this->assertEquals(0x007f0000, $address->toInt());
		$this->assertEquals('0.127.0.0', $address->toString());

		$address = Plutonium_Utility_Address::newInstance(0x7F0000);

		$this->assertEquals(0x007f0000, $address->toInt());
		$this->assertEquals('0.127.0.0', $address->toString());

		$address = Plutonium_Utility_Address::newInstance('1.127.0.0.1');

		$this->assertEquals(0x7f000001, $address->toInt());
		$this->assertEquals('127.0.0.1', $address->toString());
	}
}

?>