<?php
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
        $accepted = (boolean) $this->isAccepted();
        if ($accepted === false && $this->current() instanceof PageInterface) {
            $parent = $this->current()->getParent();
            if (!empty($parent)) {
                $parent->removePage($this->current());
            }
        }
        return $accepted;
    }

    /**
     * @return boolean
     */
    abstract protected function isAccepted();
}
