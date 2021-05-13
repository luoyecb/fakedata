<?php
namespace Luoyecb\Generator;

use Luoyecb\Util\RandUtil;

class EmailGenerator extends WordGenerator {
	private $mailSuffix = array(
		'@gmail.com',
		'@qq.com',
		'@163.com',
		'@126.com',
		'@sina.com',
		'@sohu.com',
		'@sogou.com',
	);

	public function createData() {
		$word = parent::createData();
		$index = RandUtil::randGe0LeMax(count($this->mailSuffix)-1);
		return $word . $this->mailSuffix[$index];
	}
}
