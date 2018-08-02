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

if (version_compare(phpversion(), '7.2', '>=')) {
    \class_alias(Date72::class, AbstractDate::class);
} else {
    \class_alias(Date71::class, AbstractDate::class);
}

class Date extends AbstractDate
{
}
