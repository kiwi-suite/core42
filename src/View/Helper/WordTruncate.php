<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper;

use Zend\View\Helper\AbstractHelper;

class WordTruncate extends AbstractHelper
{
    /**
     *
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
