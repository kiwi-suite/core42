<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\View\Helper;

use Zend\Stdlib\ArrayUtils;
use Zend\View\Helper\AbstractHelper;

class ArrayContainer extends AbstractHelper implements \Countable, \Iterator
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $originalData = [];

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = array_values($data);
        $this->originalData = $this->data;
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->data = $this->originalData;

        return $this;
    }

    /**
     * @param $callback
     * @param null $flag
     * @return $this
     */
    public function filter($callback, $flag = null)
    {
        $this->data = ArrayUtils::filter($this->data, $callback, $flag);

        return $this;
    }

    /**
     * @param $function
     * @return $this
     */
    public function sort($function)
    {
        uasort($this->data, $function);

        return $this;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->data[$this->position];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->position;
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
        return array_key_exists($this->position, $this->data);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->position = 0;
    }
}
