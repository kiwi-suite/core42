<?php
namespace Core42\Hydrator;

use Zend\Stdlib\Hydrator\Filter\FilterInterface;

interface FilterProviderInterface
{
    /**
     * Provides a filter for hydration - unlike the ZF2 build-in FilterProviderInterface,
     * the pre-registered filters are not ignored. Works only with the Core42 ModelHydrator
     *
     * @return FilterInterface
     */
    public function getFilter();
}
