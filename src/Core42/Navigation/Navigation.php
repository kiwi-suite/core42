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
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface;

class Navigation
{
    const EVENT_IS_ALLOWED = 'isAllowed';

    /**
     * @var Container[]
     */
    protected $containers = array();

    /**
     * @var EventManagerInterface[]
     */
    protected $events = array();

    /**
     * @var array
     */
    protected $hrefCache = array();

    /**
     * @var bool
     */
    protected $isActiveRecursion = true;

    /**
     * @var array
     */
    protected $isActiveCache = array();

    /**
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * Inject an EventManager instance
     *
     * @param string $containerName
     * @param  EventManagerInterface $eventManager
     * @return $this
     */
    public function setEventManager($containerName, EventManagerInterface $eventManager)
    {
        $eventManager->setIdentifiers(array(
            'Zend\Stdlib\DispatchableInterface',
            __CLASS__,
            get_called_class()
        ));
        $this->events[$containerName] = $eventManager;

        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @param string $containerName
     * @return EventManagerInterface
     */
    public function getEventManager($containerName)
    {
        if (!isset($this->events[$containerName])) {
            $this->setEventManager($containerName, new EventManager());
        }

        return $this->events[$containerName];
    }

    /**
     * @param Page $page
     * @trigger self::EVENT_IS_ALLOWED
     * @return bool
     */
    public function isAllowed(Page $page)
    {
        $event = new NavigationEvent();
        $event->setNavigation($this)
            ->setTarget($page);

        $results = $this->getEventManager($page->getContainerName())->trigger(self::EVENT_IS_ALLOWED, $event);
        $result  = $results->last();

        return null === $result ? true : (bool) $results->last();
    }

    /**
     * @param RouteMatch $routeMatch
     * @return Navigation
     */
    public function setRouteMatch($routeMatch)
    {
        $this->routeMatch = $routeMatch;
        return $this;
    }

    /**
     * @return RouteMatch
     */
    public function getRouteMatch()
    {
        return $this->routeMatch;
    }

    /**
     * @param \Zend\Mvc\Router\RouteStackInterface $router
     * @return $this
     */
    public function setRouter($router)
    {
        $this->router = $router;
        return $this;
    }

    /**
     * @return \Zend\Mvc\Router\RouteStackInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param string $name
     * @param Container $container
     * @return Navigation
     * @throws \InvalidArgumentException on duplicate container
     */
    public function addContainer($name, Container $container)
    {
        if ($this->hasContainer($name)) {
            throw new \InvalidArgumentException(sprintf(
                'A container with name "%s" already exists.',
                $name
            ));
        }
        $this->containers[$name] = $container;

        return $this;
    }

    /**
     * @param string $name
     * @return Container
     * @throws \InvalidArgumentException on missing container
     */
    public function getContainer($name)
    {
        if (!$this->hasContainer($name)) {
            throw new \InvalidArgumentException(sprintf(
                'No container with name "%s" could be found.',
                $name
            ));
        }

        return $this->containers[$name];
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasContainer($name)
    {
        return isset($this->containers[$name]);
    }

    /**
     * @return array
     */
    public function getContainers()
    {
        return $this->containers;
    }

    /**
     * @param string $name
     * @return Navigation
     * @throws \InvalidArgumentException on missing container
     */
    public function removeContainer($name)
    {
        if (!$this->hasContainer($name)) {
            throw new \InvalidArgumentException(sprintf(
                'No container with name "%s" could be found.',
                $name
            ));
        }
        unset($this->containers[$name]);

        return $this;
    }

    /**
     * @return Navigation
     */
    public function clearContainers()
    {
        $this->containers = array();

        return $this;
    }

    /**
     * @param Page $page
     * @return bool
     */
    public function isActive(Page $page)
    {
        $hash = spl_object_hash($page);

        if (isset($this->isActiveCache[$hash])) {
            return $this->isActiveCache[$hash];
        }

        $active = false;
        if ($this->getRouteMatch()) {
            $name = $this->getRouteMatch()->getMatchedRouteName();

            if ($page->getOption('route') == $name) {
                $reqParams = array_merge($this->getRouteMatch()->getParams(), $_GET);
                $pageParams = array_merge(
                    $page->getOption('params') ? $page->getOption('params') : array(),
                    $page->getOption('query_params') ? $page->getOption('query_params') : array()
                );
                $ignoreParams = array_merge(
                    array('__CONTROLLER__', '__NAMESPACE__', 'controller', 'action'),
                    $page->getOption('ignore_params') ? $page->getOption('ignore_params') : array()
                );

                $active = $this->paramsAreEqual($pageParams, $reqParams, $ignoreParams);
            } elseif ($this->getIsActiveRecursion()) {
                $iterator = new \RecursiveIteratorIterator($page, \RecursiveIteratorIterator::CHILD_FIRST);

                /** @var Page $page */
                foreach ($iterator as $leaf) {
                    if (!$leaf instanceof Page) {
                        continue;
                    }
                    if ($this->isActive($leaf)) {
                        $active = true;
                        break;
                    }
                }
            }
        }

        $this->isActiveCache[$hash] = $active;

        return $active;
    }

    /**
     * @param Page $page
     * @return null|string
     */
    public function getHref(Page $page)
    {
        $hash = spl_object_hash($page);

        if (array_key_exists($hash, $this->hrefCache)) {
            return $this->hrefCache[$hash];
        }

        $href = null;

        if ($page->getOption('uri')) {
            $href = $page->getOption('uri');
        } elseif ($page->getOption('route')) {
            $params = array();
            $routeMatch = $this->getRouteMatch();
            if ($routeMatch !== null) {
                $rmParams = $routeMatch->getParams();

                if (isset($rmParams[ModuleRouteListener::ORIGINAL_CONTROLLER])) {
                    $rmParams['controller'] = $rmParams[ModuleRouteListener::ORIGINAL_CONTROLLER];
                    unset($rmParams[ModuleRouteListener::ORIGINAL_CONTROLLER]);
                }

                if (isset($rmParams[ModuleRouteListener::MODULE_NAMESPACE])) {
                    unset($rmParams[ModuleRouteListener::MODULE_NAMESPACE]);
                }

                $params = array_merge($rmParams, (array) $page->getOption('params'));
            }

            $href = $this->getRouter()->assemble(
                $params,
                array('name' => $page->getOption('route'))
            );
        } elseif ($page->getOption('href')) {
            $href = $page->getAttribute('href');
        }

        if ($href) {
            if ($page->getOption('query_params')) {
                $href .= '?' . http_build_query($page->getOption('query_params'));
            }

            if ($page->getOption('fragment')) {
                $href .= '#' . trim($page->getOption('fragment'), '#');
            }
        }

        $this->hrefCache[$hash] = $href;

        return $href;
    }

    /**
     * @param boolean $isActiveRecursion
     * @return Navigation
     */
    public function setIsActiveRecursion($isActiveRecursion)
    {
        if ($isActiveRecursion != $this->isActiveRecursion) {
            $this->isActiveRecursion = $isActiveRecursion;
            $this->isActiveCache     = array();
        }
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActiveRecursion()
    {
        return $this->isActiveRecursion;
    }

    /**
     * @param $pageParams
     * @param $requiredParams
     * @param $ignoreParams
     * @return bool
     */
    protected function paramsAreEqual($pageParams, $requiredParams, $ignoreParams)
    {
        foreach ($ignoreParams as $unsetKey) {
            if (isset($requiredParams[$unsetKey])) {
                unset($requiredParams[$unsetKey]);
            }
        }
        $diff = array_diff($requiredParams, $pageParams);

        return empty($diff);
    }
}
