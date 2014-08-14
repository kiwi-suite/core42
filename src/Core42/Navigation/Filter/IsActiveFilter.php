<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Navigation\Filter;

use Core42\Navigation\Navigation;

class IsActiveFilter extends \RecursiveFilterIterator
{
    /**
     * @var Navigation
     */
    protected $navigation;

    public function __construct(\RecursiveIterator $container, Navigation $navigation)
    {
        $this->navigation = $navigation;
        parent::__construct($container);
    }


    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Check whether the current element of the iterator is acceptable
     * @link http://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     */
    public function accept()
    {
        return $this->navigation->isActive($this->current());
    }

    /**
     * @return IsActiveFilter|\RecursiveFilterIterator
     */
    public function getChildren()
    {
        return new self($this->getInnerIterator()->getChildren(), $this->navigation);
    }
}
