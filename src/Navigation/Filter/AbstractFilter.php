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

namespace Core42\Navigation\Filter;

use Core42\Navigation\Page\PageInterface;

abstract class AbstractFilter extends \RecursiveFilterIterator
{
    /**
     * Check whether the current element of the iterator is acceptable
     * @link http://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     * @since 5.1.0
     */
    public function accept()
    {
        $accepted = (bool) $this->isAccepted();
        if ($accepted === false && $this->current() instanceof PageInterface) {
            $parent = $this->current()->getParent();
            if (!empty($parent)) {
                $parent->removePage($this->current());
            }
        }

        return $accepted;
    }

    /**
     * @return bool
     */
    abstract protected function isAccepted();
}
