<?php
// ini_set('phar.readonly', true);

$index = 'fakedata.php';
$pharFileName = 'fakedata.phar';

$phar = new Phar($pharFileName);
$phar->buildFromDirectory('./');
$phar->compressFiles(Phar::GZ);
$phar->setStub("#!/usr/bin/env php\n" . Phar::createDefaultStub($index));
