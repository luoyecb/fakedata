<?php
namespace Luoyecb;

use Luoyecb\IGenerator;
use Luoyecb\Util\RandUtil;

if (!defined('FLOAT_PRECISION')) {
    define('FLOAT_PRECISION', 2);
}
if (!defined('FLOAT_MAX')) {
    define('FLOAT_MAX', 100000);
}

class DecimalGenerator implements IGenerator
{
    public function createData() {
        $float = RandUtil::randFloat() * RandUtil::rand(FLOAT_MAX);
        return sprintf('%.'.FLOAT_PRECISION.'f', $float);
    }
}
