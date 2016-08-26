<?php
namespace Core42\Navigation;

use Core42\Navigation\Page\PageInterface;


class Container implements ContainerInterface
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
     * @var array
     */
    protected $sort = [];


    /**
     * @param PageInterface $page
     */
    public function addPage(PageInterface $page)
    {
        $hash = spl_object_hash($page);

        $this->children[$hash] = $page;
        $this->sort[] = $hash;
    }

    /**
     * @param PageInterface $page
     */
    public function removePage(PageInterface $page)
    {
        /** @var PageInterface $page */
        foreach ($this->children as $hash => $child) {
            if ($page != $child) {
                continue;
            }
            unset($this->children[$hash]);

            $sortKey = array_search($hash, $this->sort);
            if ($this->index >= $sortKey && $this->index > 0) {
                $this->index--;
            }

            unset($this->sort[$sortKey]);
            $this->sort = array_values($this->sort);
        }
    }

    /**
     *
     */
    public function sort()
    {
        $index = 0;
        $sort = [];

        /** @var PageInterface $page */
        foreach ($this->children as $hash => $page) {
            $order = $page->getOrder();
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
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->children[$this->sort[$this->index]];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->sort[$this->index]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * Returns if an iterator can be created for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     * @since 5.1.0
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * Returns an iterator for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.getchildren.php
     * @return \RecursiveIterator An iterator for the current entry.
     * @since 5.1.0
     */
    public function getChildren()
    {
        return $this->children[$this->sort[$this->index]];
    }

}
