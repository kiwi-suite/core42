<?php
namespace Core42\Permissions\Rbac\Guard;

use Zend\EventManager\AbstractListenerAggregate;

abstract class AbstractGuard extends AbstractListenerAggregate implements GuardInterface
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}
