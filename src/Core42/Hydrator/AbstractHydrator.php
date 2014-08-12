<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Hydrator;

use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Stdlib\Hydrator\Filter\FilterComposite;
use Zend\Stdlib\Hydrator\Filter\MethodMatchFilter;

class AbstractHydrator extends ClassMethods
{
    /**
     * @var bool
     */
    protected $filterInitialized = false;

    /**
     *
     * @param boolean $underscoreSeparatedKeys
     */
    public function __construct($underscoreSeparatedKeys = false)
    {
        parent::__construct($underscoreSeparatedKeys);
        $this->filterComposite
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
        if (!is_object($object)) {
            return;
        }

        if ($this->filterInitialized === true) {
            return;
        }

        $this->filterInitialized = true;

        if ($object instanceof FilterProviderInterface) {
            $this->addFilter("objectFilter", $object->getFilter(), FilterComposite::CONDITION_AND);
        }
    }
}
