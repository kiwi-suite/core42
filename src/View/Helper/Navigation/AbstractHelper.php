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

namespace Core42\View\Helper\Navigation;

use Core42\Navigation\Service\FilterPluginManager;
use Core42\Navigation\Service\NavigationPluginManager;

abstract class AbstractHelper extends \Zend\Form\View\Helper\AbstractHelper
{
    /**
     * @var NavigationPluginManager
     */
    protected $navigationPluginManager;

    /**
     * @var FilterPluginManager
     */
    protected $filterPluginManager;

    /**
     * @var string
     */
    protected $container;

    /**
     * @var string
     */
    protected $partial;

    /**
     * @var int
     */
    protected $minDepth = -1;

    /**
     * @var int
     */
    protected $maxDepth = -1;

    /**
     * @var array
     */
    protected $filter = [];

    /**
     * @param NavigationPluginManager $navigationPluginManager
     * @param FilterPluginManager $filterPluginManager
     */
    public function __construct(
        NavigationPluginManager $navigationPluginManager,
        FilterPluginManager $filterPluginManager
    ) {
        $this->navigationPluginManager = $navigationPluginManager;
        $this->filterPluginManager = $filterPluginManager;
    }

    /**
     * @param null|string $partial
     * @param null|string $container
     * @return $this
     */
    public function __invoke($partial = null, $container = null)
    {
        if ($partial !== null) {
            $this->setPartial($partial);
        }

        if ($container !== null) {
            $this->setContainer($container);
        }

        return $this;
    }

    /**
     * @param string $container
     * @return $this
     */
    public function setContainer($container)
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @param $partial
     * @return $this;
     */
    public function setPartial($partial)
    {
        $this->partial = $partial;

        return $this;
    }

    /**
     * @param int $minDepth
     * @return $this
     */
    public function setMinDepth($minDepth)
    {
        $this->minDepth = $minDepth;

        return $this;
    }

    /**
     * @param int $maxDepth
     * @return $this
     */
    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;

        return $this;
    }

    /**
     * @return string
     */
    abstract public function render();

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param string $filterName
     * @param array $options
     * @return $this
     */
    public function enableFilter($filterName, array $options = [])
    {
        $this->filter[$filterName] = $options;

        return $this;
    }

    public function reset()
    {
        $this->partial = null;
        $this->minDepth = -1;
        $this->maxDepth = -1;
        $this->filter = [];
        $this->container = null;
    }
}
