<?php
namespace Luoyecb\Generator;

use Luoyecb\IGenerator;
use Luoyecb\Util\RandUtil;

class PhoneNumberGenerator implements IGenerator {
    private $phoneNumberHeads = [
        // China Mobile
        134, 135, 136, 137, 138, 139, 147, 150, 151, 152, 157, 158, 159, 1705, 178, 182, 183, 184, 187, 188,
        // China Unicom
        130, 131, 132, 145, 155, 156, 1707, 1708, 1709, 1718, 1719, 176, 185, 186,
        // China Telecom
        133, 153, 1700, 1701, 177, 180, 181, 189,
        // virtual operators
        170, 171, 176,
    ];

    public function createData() {
        $index = RandUtil::randGe0LeMax(count($this->phoneNumberHeads)-1);
        $phone = $this->phoneNumberHeads[$index];
        $phone .= RandUtil::randNumber(11 - strlen($phone));
        return $phone;
    }
}
