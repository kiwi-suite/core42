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

class DatabaseHydrator extends AbstractHydrator
{

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
     * @see \Zend\Stdlib\Hydrator\AbstractHydrator::extractValue
     */
    public function extractValue($name, $value, $object = null)
    {
        if ($this->hasStrategy($name) && $value !== null) {
            $strategy = $this->getStrategy($name);
            $value = $strategy->extract($value, $object);
        }
        return $value;
    }

    /**
     * @see \Zend\Stdlib\Hydrator\AbstractHydrator::hydrateValue
     */
    public function hydrateValue($name, $value, $data = null)
    {
        if ($this->hasStrategy($name) && $value !== null) {
            $strategy = $this->getStrategy($name);
            $value = $strategy->hydrate($value, $data);
        }
        return $value;
    }

}
