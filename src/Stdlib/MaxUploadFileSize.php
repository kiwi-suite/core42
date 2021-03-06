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

abstract class MaxUploadFileSize
{
    /**
     * @var int
     */
    private static $maxFileSize;

    /**
     * @return float|int
     */
    public static function getSize()
    {
        if (self::$maxFileSize === null) {
            // Start with post_max_size.
            $maxSize = self::parseSize(\ini_get('post_max_size'));

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $uploadMax = self::parseSize(\ini_get('upload_max_filesize'));
            if ($uploadMax > 0 && $uploadMax < $maxSize) {
                $maxSize = $uploadMax;
            }
            self::$maxFileSize = $maxSize;
        }

        return self::$maxFileSize;
    }

    /**
     * @param $size
     * @return float
     */
    protected static function parseSize($size)
    {
        $unit = \preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = \preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to
            // multiply a kilobyte by.
            return \round($size * 1024** \mb_stripos('bkmgtpezy', $unit[0]));
        } else {
            return \round($size);
        }
    }
}
