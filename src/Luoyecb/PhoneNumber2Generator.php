<?php
namespace Luoyecb;

use Luoyecb\IGenerator;
use Luoyecb\Util\RandUtil;

class PhoneNumber2Generator implements IGenerator
{
    public function createData() {
        return '1' . RandUtil::randNumber(10);
    }
}
