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

namespace Core42\Navigation\Filter;

use Core42\Navigation\Page\PageInterface;

abstract class AbstractFilter extends \RecursiveFilterIterator
{
    /**
     * Check whether the current element of the iterator is acceptable
     * @see http://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false
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
