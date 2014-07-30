<?php
namespace Core42\Permissions\Rbac\Guard;

interface GuardInterface
{
    /**
     * @param array $options
     */
    public function setOptions(array $options);
}
