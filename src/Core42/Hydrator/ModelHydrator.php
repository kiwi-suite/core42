<?php
namespace Core42\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;

class ModelHydrator extends ClassMethods
{
    public function __construct($underscoreSeparatedKeys = false)
    {
        parent::__construct($underscoreSeparatedKeys);
    }
}