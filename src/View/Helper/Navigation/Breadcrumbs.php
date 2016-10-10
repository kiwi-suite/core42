<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/raum42/core42
 * @copyright Copyright (c) 2010 - 2016 raum42 (https://www.raum42.at)
 * @license MIT License
 * @author raum42 <kiwi@raum42.at>
 */

namespace Core42\View\Helper\Navigation;

use Core42\Navigation\Filter\IsActiveFilter;
use Core42\Navigation\Filter\IsAllowedFilter;

class Breadcrumbs extends AbstractHelper
{
    /**
     * @return string
     */
    public function render()
    {
        $filter = new IsActiveFilter($this->getContainer($this->container), $this->navigation);
        $filter = new IsAllowedFilter($filter, $this->navigation);
        $iterator = new \RecursiveIteratorIterator($filter, \RecursiveIteratorIterator::SELF_FIRST);
        $iterator->setMaxDepth($this->maxDepth);

        $model = [
            'iterator' => $iterator,
            'minDepth' => $this->minDepth,
        ];

        return $this->view->render($this->partial, $model);
    }
}
