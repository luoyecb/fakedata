<?php
use PHPUnit\Framework\TestCase;
use Luoyecb\Util\RandUtil;

class RandUtilTest extends TestCase {

	const MAX_TIMES = 10000;

	public function test_randGe0() {
		for ($i = 0; $i < self::MAX_TIMES; $i++) {
			$this->assertGreaterThanOrEqual(0, RandUtil::randGe0());
		}
	}

	public function test_randGe0LeMax() {
		for ($i = 0; $i < self::MAX_TIMES; $i++) {
			$this->assertGreaterThanOrEqual(0, RandUtil::randGe0LeMax(1000000));
			$this->assertLessThanOrEqual(1000000, RandUtil::randGe0LeMax(1000000));
		}
	}

	public function test_rand() {
		for ($i = 0; $i < self::MAX_TIMES; $i++) {
			$this->assertGreaterThanOrEqual(100, RandUtil::rand(100, 10000));
			$this->assertLessThanOrEqual(10000, RandUtil::rand(100, 10000));
		}
	}

	public function test_randFloat() {
		for ($i = 0; $i < self::MAX_TIMES; $i++) {
			$this->assertGreaterThanOrEqual(0, RandUtil::randFloat());
			$this->assertLessThanOrEqual(1, RandUtil::randFloat());
		}
	}

	public function test_randFloat2() {
		for ($i = 0; $i < self::MAX_TIMES; $i++) {
			$this->assertGreaterThanOrEqual(3, RandUtil::randFloat2(3, 10));
			$this->assertLessThanOrEqual(10, RandUtil::randFloat2(3, 10));
		}
	}

	public function test_randNumber() {
		for ($i = 0; $i < self::MAX_TIMES; $i++) {
			$len = RandUtil::rand(2, 10);
			$this->assertEquals($len, strlen(RandUtil::randNumber($len)));
		}
	}

}
