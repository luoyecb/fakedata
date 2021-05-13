<?php
namespace Luoyecb\Generator;

use Luoyecb\IGenerator;
use Luoyecb\Util\RandUtil;

if (!defined('FLOAT_PRECISION_MAX')) {
    define('FLOAT_PRECISION_MAX', 3);
}
if (!defined('FLOAT_MAX')) {
    define('FLOAT_MAX', 100000);
}

class DecimalGenerator implements IGenerator {
    public function createData() {
        $float = RandUtil::randFloat() * RandUtil::randGe0LeMax(FLOAT_MAX);
        $prec = RandUtil::randGe0LeMax(FLOAT_PRECISION_MAX);
        return sprintf('%.'.$prec.'f', $float);
    }
}
