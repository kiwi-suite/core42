<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Navigation;

use Zend\EventManager\Event;

class NavigationEvent extends Event
{
    /**
     * @var
     */
    protected $navigation;

    /**
     * @var mixed
     */
    protected $result;

    /**
     * @var string
     */
    protected $containerName;

    /**
     * @param Navigation $navigation
     * @return NavigationEvent
     */
    public function setNavigation(Navigation $navigation)
    {
        $this->navigation = $navigation;

        return $this;
    }

    /**
     * @return Navigation
     */
    public function getNavigation()
    {
        return $this->navigation;
    }

    /**
     * @param mixed $result
     * @return NavigationEvent
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param string $containerName
     */
    public function setContainerName($containerName)
    {
        $this->containerName = $containerName;
    }

    /**
     * @return string
     */
    public function getContainerName()
    {
        return $this->containerName;
    }
}
