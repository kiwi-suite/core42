<?php
/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
 */

chdir(__DIR__);

if (file_exists('../vendor/autoload.php')) {
    include '../vendor/autoload.php';
} elseif (file_exists('../../../../vendor/autoload.php')) {
    include '../../../../vendor/autoload.php';
}
