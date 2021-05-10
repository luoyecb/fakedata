<?php
namespace Luoyecb;

use Luoyecb\IGenerator;
use Luoyecb\FactoryGenerator;
use Luoyecb\Util\RandUtil;

class EmailGenerator implements IGenerator
{
	private $mailSuffix = array(
		'@gmail.com',
		'@yahoo.com',
		'@msn.com',
		'@hotmail.com',
		'@foxmail.com',
		'@qq.com',
		'@163.com',
		'@163.net',
		'@googlemail.com',
		'@126.com',
		'@sina.cn',
		'@sina.com',
		'@sohu.com',
		'@yahoo.com.cn',
		'@tom.com',
		'@sogou.com',
		'@netvigator.com',
	);
	private $suffixLen = 17;

	public function createData() {
		$woreGe = FactoryGenerator::create('word');
		return $woreGe->createData() . $this->mailSuffix[ RandUtil::rand($this->suffixLen - 1) ];
	}
}
