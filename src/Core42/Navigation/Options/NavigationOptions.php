<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Navigation\Options;

use Zend\Stdlib\AbstractOptions;

class NavigationOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $containers = [];

    /**
     * @var array
     */
    protected $listeners = [];

    /**
     * @param array $containers
     * @return $this
     */
    public function setContainers($containers)
    {
        $this->containers = $containers;

        return $this;
    }

    /**
     * @return array
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * @param array $listeners
     * @return $this
     */
    public function setListeners($listeners)
    {
        $this->listeners = $listeners;

        return $this;
    }

    /**
     * @return array
     */
    public function getListeners()
    {
        return $this->listeners;
    }
}
