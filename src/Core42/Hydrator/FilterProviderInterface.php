<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

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
