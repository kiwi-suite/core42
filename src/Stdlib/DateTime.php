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

namespace Core42\Stdlib;

if (\version_compare(\phpversion(), '7.2', '>=')) {
    \class_alias(DateTime72::class, AbstractDateTime::class);
} else {
    \class_alias(DateTime71::class, AbstractDateTime::class);
}

class DateTime extends AbstractDateTime
{
}
