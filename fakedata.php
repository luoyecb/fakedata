<?php
include 'vendor/autoload.php';

use Luoyecb\ArgParser;
use Luoyecb\FactoryGenerator;

function execMain() {
    $parser = new ArgParser();
    $parser->addBool('help', false, 'Show this help information.')
        ->addBool('n', false, 'Do not output line breaks.')
        ->addInt('times', 1, "Number of times generated.")
        ->addString('key', '', 'KEY_NAME')
        ->addString('format', '', 'FORMAT_STRING')
        ->parse();
    extract($parser->getOptions());

    global $argc;
    if ($help || $argc == 1) {
        printUsage($parser);
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

function printUsage(ArgParser $parser) {
    $usage = $parser->buildUsage();
    $binName = getBinName();
    echo <<<"USAGE_STR"
{$usage}
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
  {$binName} -key name
  {$binName} -format 'My name is {name}.'


USAGE_STR;
}

function getBinName() {
    global $argv;
    return basename($argv[0], '.php');
}

if (PHP_SAPI == 'cli') {
    execMain();
}
