<?php
namespace Luoyecb\Util;

class StringUtil
{
    public static function shuffle($str, $len) {
        return substr(str_shuffle($str), 0, $len);
    }
}
