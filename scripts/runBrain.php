#!/usr/bin/php -f
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use RPurinton\Discommand2\Core\Brain;

if (!isset($argv[1])) die("Usage: runBrain.php <name>\n");
$myName = $argv[1];

new Brain($myName);