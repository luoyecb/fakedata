<?php
namespace Luoyecb;

class FactoryGenerator {
    private static $classmaps = [
        'phone'     => \Luoyecb\Generator\PhoneNumberGenerator::class,
        'phone2'    => \Luoyecb\Generator\PhoneNumber2Generator::class,
        'uuid'      => \Luoyecb\Generator\UUIDGenerator::class,
        'decimal'   => \Luoyecb\Generator\DecimalGenerator::class,
        'date'      => \Luoyecb\Generator\DateGenerator::class,
        'time'      => \Luoyecb\Generator\TimeGenerator::class,
        'datetime'  => \Luoyecb\Generator\DateTimeGenerator::class,
        'word'      => \Luoyecb\Generator\WordGenerator::class,
        'word2'     => \Luoyecb\Generator\Word2Generator::class,
        'email'     => \Luoyecb\Generator\EmailGenerator::class,
        'chinese'   => \Luoyecb\Generator\ChineseGenerator::class,
        'chinese2'  => \Luoyecb\Generator\Chinese2Generator::class,
        'name'      => \Luoyecb\Generator\NameGenerator::class,
        'name2'     => \Luoyecb\Generator\Name2Generator::class,
        'manname'   => \Luoyecb\Generator\ManNameGenerator::class,
        'womanname' => \Luoyecb\Generator\WomanNameGenerator::class,
    ];
    private static $objectContainer = [];

    public static function create($geKey) {
        if (!isset(self::$classmaps[$geKey])) {
            return null;
        }
        if (!isset(self::$objectContainer[$geKey])) {
            self::$objectContainer[$geKey] = new self::$classmaps[$geKey]();
        }
        return self::$objectContainer[$geKey];
    }

    public static function register($geKey, $className) {
        if (!isset(self::$classmaps[$geKey]) && class_exists($className)) {
            self::$classmaps[$geKey] = $className;
        }
    }

    public static function __callStatic($name, $args) {
        if (!isset(self::$classmaps[$name])) {
            return null;
        }
        $obj = self::create($name);
        return $obj->createData();
    }

    public static function formatString($string) {
        return preg_replace_callback(
            '/\{\s*(\w+)\s*\}/i',
            [__CLASS__, '_formatStringCallback'],
            $string);
    }

    public static function _formatStringCallback($matchs) {
        $key = $matchs[1];
        if (!isset(self::$classmaps[$key])) {
            return $matchs[0];
        }
        $obj = self::create($key);
        return $obj->createData();
    }
}
