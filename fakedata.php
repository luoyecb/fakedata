<?php
include 'vendor/autoload.php';

use Luoyecb\ArgParser;
use Luoyecb\FactoryGenerator;

function execMain() {
    $parser = new ArgParser();
    $parser->addBool('help', false);
    $parser->addInt('n', 1);
    $parser->addString('key', '');
    $parser->addString('format', '');
    $parser->parse();
    extract($parser->getOptions());

    global $argc;
    if ($help || $argc == 1) {
        printUsage();
        return;
    }

    if (!empty($key)) {
        $ret = FactoryGenerator::$key();
        if ($ret) {
            echo $ret, "\n";
        }
    } else if ($format) {
        for ($j = 0; $j < $n; $j++) {
            echo FactoryGenerator::formatString($format);
            echo "\n";
        }
    }
}

function printUsage() {
    global $argv;
    $basename = basename($argv[0]);
    echo <<<"USAGE_STR"
Usage:
    php {$basename} [option]

option:
    -help:   show help information
    -key:    KEY_NAME
    -format: FORMAT_STRING
    -n:      NUM

KEY_NAME:
    phone, phone2
    uuid
    decimal
    date, time, datetime
    word, word2
    email
    chinese, chinese2
    name, name2, manname, womanname

FORMAT_STRING:
    insert into tb_demo values ('{phone}', '{email}');
USAGE_STR;
    echo "\n\n";
}

if (PHP_SAPI == 'cli') {
    execMain();
} else {
    exit('Please run under the commnad line.');
}
