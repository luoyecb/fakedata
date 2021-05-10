<?php
namespace Luoyecb\Util;

class RandUtil
{
    // 生成区间[min, max]的整数
    public static function rand($max = NULL, $min = 0) {
        if($max === NUll) {
            $max = mt_getrandmax();
        }
        return mt_rand($min, $max);
    }

    // 生成区间[0, 1]的浮点数
    public static function randFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    // 生成数字字符串
    public static function randNumber($length) {
        $numberStr = '';
        while ($length > 4) {
            $numberStr .= self::rand(9999, 1000);
            $length -= 4;
        }
        $numberStr .= self::rand(pow(10, $length)-1, pow(10, $length-1));
        return $numberStr;
    }
}
