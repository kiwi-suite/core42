<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Navigation;

use Core42\Navigation\Page\Page;

class Container implements \RecursiveIterator
{
    /**
     * @var int
     */
    protected $index = 0;

    /**
     * @var array
     */
    protected $children = [];

    /**
     * @var string
     */
    protected $containerName;

    /**
     * @var array
     */
    protected $sort = [];

    /**
     *
     */
    public function sort()
    {
        $index = 0;
        $sort = [];

        /** @var Page $page */
        foreach ($this->children as $hash => $page) {
            $order = $page->getOption('order');
            if ($order === null) {
                $sort[$hash] = $index;
                $index++;
            } else {
                $sort[$hash] = $order;
            }

            if ($page->hasChildren()) {
                $page->sort();
            }
        }

        asort($sort);
        $this->sort = array_keys($sort);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->children[$this->sort[$this->index]];
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->sort[$this->index]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Returns if an iterator can be created for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Returns an iterator for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.getchildren.php
     * @return \RecursiveIterator An iterator for the current entry.
     */
    public function getChildren()
    {
        return $this->children[$this->sort[$this->index]];
    }

    /**
     * @param Page $page
     * @return $this
     */
    public function addPage(Page $page)
    {
        $page->setContainerName($this->getContainerName());

        $hash = spl_object_hash($page);

        $this->children[$hash] = $page;
        $this->sort[] = $hash;

        $page->setParent($this);

        return $this;
    }

    /**
     * @param string $containerName
     * @return $this
     */
    public function setContainerName($containerName)
    {
        $this->containerName = $containerName;

        return $this;
    }

    /**
     * @return string
     */
    public function getContainerName()
    {
        return $this->containerName;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return Page|null
     */
    public function findOneByAttribute($attribute, $value)
    {
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getAttribute($attribute) == $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return Page[]
     */
    public function findByAttribute($attribute, $value)
    {
        $result = [];
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getAttribute($attribute) == $value) {
                $result[] = $page;
            }
        }

        return $result;
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return Page|null
     */
    public function findOneByOption($option, $value)
    {
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) == $value) {
                return $page;
            }
        }

        return null;
    }

    /**
     * @param string $option
     * @param mixed $value
     * @return Page[]
     */
    public function findByOption($option, $value)
    {
        $result = [];
        $iterator = new \RecursiveIteratorIterator($this, \RecursiveIteratorIterator::SELF_FIRST);

        /** @var Page $page */
        foreach ($iterator as $page) {
            if ($page->getOption($option) == $value) {
                $result[] = $page;
            }
        }

        return $result;
    }
}
