<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2017 raum42 (https://raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */


namespace Core42\View\Helper;

use Ramsey\Uuid\Uuid;
use Zend\View\Helper\AbstractHelper;

class WordTruncate extends AbstractHelper
{
    /**
     * @param string $string
     * @param $width
     * @return string
     */
    public function __invoke($string, $width)
    {
        $width = (int) $width;

        if (\mb_strlen($string) <= $width) {
            return $string;
        }

        $uuid = Uuid::uuid4()->toString();

        $wordWrap = \wordwrap($string, $width, "\n{{#wrap#" . $uuid . "}}");

        return \mb_substr($wordWrap, 0, \mb_strpos($wordWrap, "\n{{#wrap#" . $uuid . "}}"));
    }
}
