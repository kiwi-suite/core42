<?php
namespace Core42\Navigation\Page;

use Core42\Navigation\ContainerInterface;

interface PageInterface extends ContainerInterface
{
    /**
     * @param array $options
     */
    public function setOptions(array $options = []);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param mixed $name
     * @param mixed $default
     * @return mixed
     */
    public function getOption($name, $default = null);

    /**
     * @param PageInterface $page
     */
    public function setParent(PageInterface $page);

    /**
     * @return PageInterface|null
     */
    public function getParent();

    /**
     * @param string $label
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getHref();

    /**
     * @param int $order
     */
    public function setOrder($order);

    /**
     * @return int
     */
    public function getOrder();

    /**
     * @param string $permission
     */
    public function setPermission($permission);

    /**
     * @return string
     */
    public function getPermission();
}
