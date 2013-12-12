<?php
namespace Core42\Hydrator;

use Core42\Permissions\Acl\Role\RoleProviderInterface;
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
            ->addFilter("getInputFilter", new MethodMatchFilter("getInputFilter"), FilterComposite::CONDITION_AND)
            ->addFilter("hasChanged", new MethodMatchFilter("hasChanged"), FilterComposite::CONDITION_AND);
    }

    /**
     * Hydrate an object by populating getter/setter methods
     *
     * Hydrates an object by getter/setter methods of the object.
     *
     * @param  array                            $data
     * @param  object                           $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $this->addObjectDependedFilter($object);
        return parent::hydrate($data, $object);
    }

    /**
     * Extract values from an object with class methods
     *
     * Extracts the getter/setter of the given $object.
     *
     * @param  object                           $object
     * @return array
     */
    public function extract($object)
    {
        $this->addObjectDependedFilter($object);
        return parent::extract($object);
    }

    /**
     * @param object $object
     */
    public function addObjectDependedFilter($object)
    {
        //TODO register object depended filter elsewhere (initialzer like)
        if (!is_object($object)) {
            return;
        }

        if ($object instanceof RoleProviderInterface && !$this->hasFilter("getIdentityRole")) {
            $this->addFilter("getIdentityRole", new MethodMatchFilter("getIdentityRole"), FilterComposite::CONDITION_AND);
        }
    }
}
