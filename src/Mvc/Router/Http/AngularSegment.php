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

namespace Core42\Mvc\Router\Http;

use Zend\Router\Http\Segment;

class AngularSegment extends Segment
{
    /**
     * Encode a path segment.
     *
     * @param  string $value
     * @return string
     */
    protected function encode($value)
    {
        $key = (string) $value;
        if (!isset(static::$cacheEncode[$key])) {
            $isAngularVar = false;
            if (\mb_substr($value, 0, 2) == '{{' && \mb_substr($value, -2) == '}}') {
                $isAngularVar = true;
                $value = \trim(\mb_substr($value, 2, -2));
            }
            static::$cacheEncode[$key] = \rawurlencode($value);
            static::$cacheEncode[$key] = \strtr(static::$cacheEncode[$key], static::$urlencodeCorrectionMap);
            if ($isAngularVar === true) {
                static::$cacheEncode[$key] = '{{ ' . static::$cacheEncode[$key] . ' }}';
            }
        }

        return static::$cacheEncode[$key];
    }
}
