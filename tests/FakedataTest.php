<?php
use PHPUnit\Framework\TestCase;
use Luoyecb\FactoryGenerator;

class FakedataTest extends TestCase {

	public function testPhone() {
		$obj = FactoryGenerator::create('phone');
		$this->assertTrue(strlen($obj->createData()) == 11);
	}

	public function testPhone2() {
		$obj = FactoryGenerator::create('phone2');
		$this->assertTrue(strlen($obj->createData()) == 11);
	}

	public function testUUID() {
		$obj = FactoryGenerator::create('uuid');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testDecimal() {
		$obj = FactoryGenerator::create('decimal');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testDate() {
		$obj = FactoryGenerator::create('date');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testTime() {
		$obj = FactoryGenerator::create('time');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testDatetime() {
		$obj = FactoryGenerator::create('datetime');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testWord() {
		$obj = FactoryGenerator::create('word');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testWord2() {
		$obj = FactoryGenerator::create('word2');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testEmail() {
		$obj = FactoryGenerator::create('email');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testChinese() {
		$obj = FactoryGenerator::create('chinese');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testChinese2() {
		$obj = FactoryGenerator::create('chinese2');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testName() {
		$obj = FactoryGenerator::create('name');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testName2() {
		$obj = FactoryGenerator::create('name2');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testManName() {
		$obj = FactoryGenerator::create('manname');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

	public function testWomanName() {
		$obj = FactoryGenerator::create('womanname');
		$this->assertTrue(strlen($obj->createData()) > 0);
	}

}
