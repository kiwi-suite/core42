<?php
namespace Core42\View\Helper\Navigation;

use Core42\Navigation\Navigation;

abstract class AbstractHelper extends \Zend\Form\View\Helper\AbstractHelper
{
    /**
     * @var Navigation
     */
    protected $navigation;

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
     * @param Navigation $navigation
     */
    public function __construct(Navigation $navigation)
    {
        $this->navigation = $navigation;
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
     * @param string $name
     * @return \Core42\Navigation\Container
     */
    public function getContainer($name)
    {
        return $this->navigation->getContainer($name);
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
}
