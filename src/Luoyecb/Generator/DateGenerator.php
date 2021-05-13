<?php
namespace Luoyecb\Generator;

use Luoyecb\IGenerator;
use Luoyecb\Util\RandUtil;

class DateGenerator implements IGenerator {
	protected $formatString = 'Y-m-d';

    public function createData() {
        $op = ['-', '+'][RandUtil::randGe0()%2];
        $delta = RandUtil::randGe0()%time();
        $ts = strtotime(sprintf('%s%s sec', $op, $delta));
        return date($this->formatString, $ts);
    }
}
