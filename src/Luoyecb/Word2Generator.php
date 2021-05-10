<?php
namespace Luoyecb;

use Luoyecb\IGenerator;
use Luoyecb\Util\RandUtil;
use Luoyecb\Util\StringUtil;

class Word2Generator implements IGenerator
{
    public function createData() {
        $char_len = RandUtil::rand(10, 2);
        return StringUtil::shuffle('abcdefghijklmnopqrstuvwxyz', $char_len);
    }
}
