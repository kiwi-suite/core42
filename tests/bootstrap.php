<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

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


