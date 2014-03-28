<?php
namespace Core42\Permissions\Rbac\Guard;

use Core42\Permissions\Guard\GuardInterface;

abstract class AbstractGuard implements GuardInterface
{
    protected $options = array();

    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}
