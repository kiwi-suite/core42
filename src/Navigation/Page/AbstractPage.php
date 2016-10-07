<?php
namespace Core42\Navigation\Page;

use Core42\Navigation\Container;

abstract class AbstractPage extends Container implements PageInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * @var PageInterface
     */
    protected $parent;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var int
     */
    protected $order;

    /**
     * @var string
     */
    protected $permission;

    /**
     * @param PageInterface $page
     */
    public function addPage(PageInterface $page)
    {
        $page->setParent($this);
        parent::addPage($page);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        if (array_key_exists($name, $this->options)) {
            return $this->options[$name];
        }
        return $default;
    }

    /**
     * @param PageInterface $page
     */
    public function setParent(PageInterface $page)
    {
        $this->parent = $page;
    }

    /**
     * @return PageInterface
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param int $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param string $permission
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;
    }

    /**
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }
}
