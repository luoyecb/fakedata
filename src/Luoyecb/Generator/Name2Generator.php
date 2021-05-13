<?php
namespace Luoyecb\Generator;

use Luoyecb\Generator\NameGenerator;
use Luoyecb\Util\RandUtil;
use Luoyecb\FactoryGenerator;

class Name2Generator extends NameGenerator {
	public function createData() {
		$chineseGe = FactoryGenerator::create('chinese');

		$index = RandUtil::randGe0LeMax(count($this->surname)-1);
		$name = $this->surname[$index] . $chineseGe->createData();

		$cnt = RandUtil::randGe0()%2;
		if($index == 1) {
			$name .= $chineseGe->createData();
		}
		return $name;
	}
}
