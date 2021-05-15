<?php
use PHPUnit\Framework\TestCase;
use Luoyecb\FactoryGenerator;

class FakedataTest extends TestCase {

	public function dataProvider() {
		return [
			['phone', '==', 11],
			['phone2', '==', 11],
			['uuid', '>=', 36],
			['decimal', '>', 0],
			['date', '>', 0],
			['time', '>', 0],
			['datetime', '>', 0],
			['word', '>', 0],
			['word2', '>', 0],
			['email', '>', 1],
			['chinese', '>', 0],
			['chinese2', '>', 0],
			['name', '>=', 2],
			['name2', '>=', 2],
			['manname', '>=', 2],
			['womanname', '>=', 2],
		];
	}

	private function op($s, $op, $len): bool {
		$res = strlen($s);
		switch($op) {
			case '==': return $res == $len;
			case '>':  return $res >  $len;
			case '>=': return $res >= $len;
		}
		return true;
	}

	/**
	 * @dataProvider dataProvider
	 */
	public function testNormal($key, $op, $expected) {
		$obj = FactoryGenerator::create($key);
		$this->assertTrue($this->op($obj->createData(), $op, $expected));
	}

	public function testCreate() {
		$obj1 = FactoryGenerator::create('phone');
		$obj2 = FactoryGenerator::create('phone');

		$this->assertTrue($obj1 === $obj2);
	}

	public function testStaticCall() {
		$phone = FactoryGenerator::phone();
		$this->assertTrue(strlen($phone) == 11);
	}

	public function testFormatString() {
		$str = 'My name is {manname}, phone is {phone}.';
		$str = FactoryGenerator::formatString($str);
		var_dump($str);
		$this->assertTrue(strpos($str, '{') === false);
		$this->assertTrue(strpos($str, '}') === false);
	}

}
