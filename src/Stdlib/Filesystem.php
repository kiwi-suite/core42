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

abstract class Filesystem
{
    /**
     * @param string $file
     * @return bool
     */
    public static function isAbsolutePath($file)
    {
        return \strspn($file, '/\\', 0, 1)
        || (\mb_strlen($file) > 3 && \ctype_alpha($file[0])
            && \mb_substr($file, 1, 1) === ':'
            && \strspn($file, '/\\', 2, 1)
        )
        || null !== \parse_url($file, PHP_URL_SCHEME);
    }
}
