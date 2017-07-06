#!/usr/bin/env php
<?php
require __DIR__ . '/../vendor/autoload.php';

use Ftob\AllDifferentDirections\DifferentDirections;
use Ftob\AllDifferentDirections\Directions;

// Check args
if ($_SERVER['argc'] > 1) {
    $filePath = $_SERVER['argv'][1];
    // File exist?
    if (!file_exists($filePath)) {
        file_put_contents($filePath, '');
    }

    $file = new SplFileObject($filePath);
    $dd = new DifferentDirections($file, new Directions());

    echo (string)$dd;
} else {
    throw new Exception("Parameter file undefined, need - $ console /tmp/example.tmp");
}