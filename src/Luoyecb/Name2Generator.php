<?php
namespace Luoyecb;

use Luoyecb\IGenerator;
use Luoyecb\NameGenerator;
use Luoyecb\Util\RandUtil;

class Name2Generator extends NameGenerator
{
	public function createData() {
		$chge = FactoryGenerator::create('chinese');

		$name = $this->surname[ RandUtil::rand($this->surnameLength) ];
		$index = RandUtil::rand() % 3;
		$name .= $chge->createData();
		if($index <= 1) {
			$name .= $chge->createData();
		}
		return $name;
	}
}
