<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
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
            if (substr($value, 0, 2) == '{{' && substr($value, -2) == '}}') {
                $isAngularVar = true;
                $value = trim(substr($value, 2, -2));
            }
            static::$cacheEncode[$key] = rawurlencode($value);
            static::$cacheEncode[$key] = strtr(static::$cacheEncode[$key], static::$urlencodeCorrectionMap);
            if ($isAngularVar === true) {
                static::$cacheEncode[$key] = '{{ ' . static::$cacheEncode[$key] . ' }}';
            }
        }

        return static::$cacheEncode[$key];
    }
}
