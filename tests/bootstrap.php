<?php

chdir(__DIR__);

date_default_timezone_set("UTC");

$loader = null;
if (file_exists('../vendor/autoload.php')) {
    $loader = include '../vendor/autoload.php';
    $loader->add('Core42Test', __DIR__);
} elseif (file_exists('../../../vendor/autoload.php')) {
    $loader = include '../../../vendor/autoload.php';
    $loader->add('Core42Test', __DIR__);
}


