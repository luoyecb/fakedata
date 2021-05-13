<?php
namespace Luoyecb\Generator;

use Luoyecb\Util\RandUtil;

class WomanNameGenerator extends NameGenerator {
    public function createData() {
        $index = RandUtil::randGe0LeMax(count($this->surname)-1);
        $name = $this->surname[$index];

        $index = RandUtil::randGe0LeMax(count($this->womanLastname)-1);
        $name .= $this->womanLastname[$index];

        return $name;
    }
}
