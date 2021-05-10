<?php
namespace Luoyecb;

use Luoyecb\IGenerator;
use Luoyecb\Util\RandUtil;

class Chinese2Generator implements IGenerator
{
	// 中文的unicode编码范围： /[\x4e00-\x9fa5]+/
	private $min = 19968; // \u4e00
	private $max = 40869; // \u9fa5

	public function createData() {
		$rand = dechex(RandUtil::rand($this->max, $this->min));
		return json_decode(sprintf('"\u%s"', $rand));
	}
}
