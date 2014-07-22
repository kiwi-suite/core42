<?php
namespace Core42\Permissions\Rbac\Guard;

interface GuardInterface
{
    /**
     * @param array $options
     * @return mixed
     */
    public function setOptions(array $options);
}
