#!/usr/bin/env php
<?php
require __DIR__.'/../vendor/autoload.php';

if ($argc < 2) {
    throw new \InvalidArgumentException('You have to pass file as an argument.');
}

list(, $file) = $argv;
$input = file_get_contents($file);

$app = new \NenadalM\HoursCounter\App();
echo $app->run($input);
