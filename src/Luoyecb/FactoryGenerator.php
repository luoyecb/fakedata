<?php
namespace Luoyecb;

use Luoyecb\PhoneNumberGenerator;
use Luoyecb\PhoneNumber2Generator;
use Luoyecb\UUIDGenerator;
use Luoyecb\DecimalGenerator;
use Luoyecb\DateGenerator;
use Luoyecb\TimeGenerator;
use Luoyecb\DateTimeGenerator;
use Luoyecb\WordGenerator;
use Luoyecb\Word2Generator;
use Luoyecb\EmailGenerator;
use Luoyecb\ChineseGenerator;
use Luoyecb\Chinese2Generator;
use Luoyecb\NameGenerator;
use Luoyecb\Name2Generator;

class FactoryGenerator
{
    private static $classmaps = [
        'phone' => PhoneNumberGenerator::class,
        'phone2' => PhoneNumber2Generator::class,
        'uuid' => UUIDGenerator::class,
        'decimal' => DecimalGenerator::class,
        'date' => DateGenerator::class,
        'time' => TimeGenerator::class,
        'datetime' => DateTimeGenerator::class,
        'word' => WordGenerator::class,
        'word2' =>Word2Generator::class,
        'email' => EmailGenerator::class,
        'chinese' => ChineseGenerator::class,
        'chinese2' => Chinese2Generator::class,
        'name' => NameGenerator::class,
        'name2' => Name2Generator::class,
    ];
    private static $objectContainers = [];

    // register generator
    public static function register($geKey, $className) {
        if (!isset(self::$classmaps[$geKey]) && class_exists($className)) {
            self::$classmaps[$geKey] = $className;
        }
    }

    // factory method
    public static function create($geKey) {
        if (isset(self::$classmaps[$geKey])) {
            if (!isset(self::$objectContainers[$geKey])) {
                self::$objectContainers[$geKey] = new self::$classmaps[$geKey]();
            }
            return self::$objectContainers[$geKey];
        }
        return null;
    }

    public static function __callStatic($name, $args) {
        if (isset(self::$classmaps[$name])) {
            $obj = self::create($name);
            return $obj->createData();
        }
        return null;
    }

    public static function formatString($string) {
        return preg_replace_callback('/\{\s*(\w+)\s*\}/i',
            [__CLASS__, '_formatStringCallback'], $string);
    }

    public static function _formatStringCallback($matchs) {
        $key = $matchs[1];
        if (isset(self::$classmaps[$key])) {
            $obj = self::create($key);
            return $obj->createData();
        }
        return $matchs[0];
    }
}
