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

namespace Core42\View\Helper;

use Zend\View\Helper\AbstractHelper;

class WordTruncate extends AbstractHelper
{
    /**
     * @return string
     */
    public function __invoke($string, $width)
    {
        if (strlen($string) <= $width) {
            return $string;
        }

        $wordWrap = wordwrap($string, $width, "\n{{#wrap#}}");

        return substr($wordWrap, 0, strpos($wordWrap, "\n{{#wrap#}}"));
    }
}
