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


namespace Core42\View\Helper;

use Ramsey\Uuid\Uuid as UuidGenerator;
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

        $uuid = UuidGenerator::uuid4()->toString();

        $wordWrap = \wordwrap($string, $width, "\n{{#wrap#" . $uuid . "}}");

        return \mb_substr($wordWrap, 0, \mb_strpos($wordWrap, "\n{{#wrap#" . $uuid . "}}"));
    }
}
