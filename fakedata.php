<?php
include 'vendor/autoload.php';

use Luoyecb\ArgParser;
use Luoyecb\FactoryGenerator;

function execMain() {
    $parser = new ArgParser();
    $parser->addBool('help', false);
    $parser->addBool('n', false);
    $parser->addInt('times', 1);
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
        $ge = FactoryGenerator::create($key);
        if ($ge) {
            for ($i = 0; $i < $times; $i++) {
                echo $ge->createData();
                if (!$n) {
                    echo PHP_EOL;
                }
            }
        }
    } else if (!empty($format)) {
        for ($i = 0; $i < $times; $i++) {
            echo FactoryGenerator::formatString($format);
            if (!$n) {
                echo PHP_EOL;
            }
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
    -help:   Show this help information.
    -key:    KEY_NAME
    -format: FORMAT_STRING
    -times:  NUM
    -n:      Do not output line breaks.

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

Example:
    php {$basename} -key name
    php {$basename} -format 'My name is {name}.'
USAGE_STR;
    echo PHP_EOL;
    echo PHP_EOL;
}

if (PHP_SAPI == 'cli') {
    execMain();
} else {
    exit('Please run under the commnad line.');
}
