<?php
namespace Luoyecb\Generator;

use Luoyecb\Util\RandUtil;

class ManNameGenerator extends NameGenerator {
    public function createData() {
        $index = RandUtil::randGe0LeMax(count($this->surname)-1);
        $name = $this->surname[$index];

        $index = RandUtil::randGe0LeMax(count($this->manLastname)-1);
        $name .= $this->manLastname[$index];

        return $name;
    }
}
