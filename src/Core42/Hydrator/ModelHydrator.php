<?php
namespace Core42\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;

class ModelHydrator extends ClassMethods
{
    /**
     *
     * @param boolean $underscoreSeparatedKeys
     */
    public function __construct($underscoreSeparatedKeys = false)
    {
        parent::__construct($underscoreSeparatedKeys);
        $this->filterComposite->addFilter("getInputFilterSpecification", new MethodMatchFilter("getInputFilterSpecification"), FilterComposite::CONDITION_AND)
            ->addFilter("isValid", new MethodMatchFilter("isValid"), FilterComposite::CONDITION_AND)
            ->addFilter("isMemento", new MethodMatchFilter("isMemento"), FilterComposite::CONDITION_AND)
            ->addFilter("getHydrator", new MethodMatchFilter("getHydrator"), FilterComposite::CONDITION_AND)
            ->addFilter("getInputFilter", new MethodMatchFilter("getInputFilter"), FilterComposite::CONDITION_AND);
    }
}
