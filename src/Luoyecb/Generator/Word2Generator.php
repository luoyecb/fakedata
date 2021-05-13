<?php
namespace Luoyecb\Generator;

use Luoyecb\IGenerator;
use Luoyecb\Util\RandUtil;
use Luoyecb\Util\StringUtil;

class Word2Generator implements IGenerator {
	private $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function createData() {
        $len = RandUtil::rand(2, 10);
        return StringUtil::shuffle($this->chars, $len);
    }
}
