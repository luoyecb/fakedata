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
        if (!self::exists($geKey)) {
            return null;
        }
        if (!self::existsInstance($geKey)) {
            self::$objectContainer[$geKey] = new self::$classmaps[$geKey]();
        }
        return self::$objectContainer[$geKey];
    }

    public static function register($geKey, $className) {
        if (!self::exists($geKey) && class_exists($className)) {
            self::$classmaps[$geKey] = $className;
        }
    }

    private static function exists($geKey): bool {
        return isset(self::$classmaps[$geKey]);
    }

    private static function existsInstance($geKey): bool {
        return isset(self::$objectContainer[$geKey]);
    }

    public static function __callStatic($name, $args) {
        $obj = self::create($name);
        if ($obj !== null) {
            return $obj->createData();
        }
        return null;
    }

    public static function formatString($string) {
        return preg_replace_callback(
            '/\{\s*(\w+)\s*\}/i',
            [__CLASS__, '_formatStringCallback'],
            $string);
    }

    private static function _formatStringCallback($matchs) {
        $name = $matchs[1];
        $obj = self::create($name);
        if ($obj !== null) {
            return $obj->createData();
        }
        return $matchs[0];
    }
}
