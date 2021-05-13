<?php
namespace Luoyecb\Util;

class RandUtil {

    public static function randGe0() {
        return mt_rand();
    }

    public static function randGe0LeMax($max) {
        return mt_rand(0, $max);
    }

    public static function rand($min, $max) {
        return mt_rand($min, $max);
    }

    public static function randFloat() {
        return self::randFloat2(0, 1);
    }

    public static function randFloat2($min, $max) {
        return $min + mt_rand()/mt_getrandmax()*($max-$min);
    }

    public static function randNumber($length) {
        $s = "" . self::rand(1, 9);
        if ($length <= 1) {
            return $s;
        }

        $a = [];
        for ($i = 1; $i < $length; $i++) {
            $a[] = self::rand(0, 9);
        }
        shuffle($a);
        return $s . implode("", $a);
    }
}
