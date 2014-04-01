<?php
namespace Core42\Permissions\Rbac\Guard;

use Core42\Permissions\Guard\GuardInterface;
use Zend\EventManager\AbstractListenerAggregate;

abstract class AbstractGuard extends AbstractListenerAggregate implements GuardInterface
{
    protected $options = array();

    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}
