<?php
namespace Luoyecb;

use Luoyecb\IGenerator;
use Luoyecb\Util\RandUtil;

class DateTimeGenerator implements IGenerator
{
    public function createData() {
        $op = ['-', '+'][ RandUtil::rand()%2 ];
        $ts = strtotime(sprintf('%s%s sec', $op, RandUtil::rand(100000000)));
        return date('Y-m-d H:i:s', $ts);
    }
}
