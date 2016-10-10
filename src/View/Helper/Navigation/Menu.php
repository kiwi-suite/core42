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

class Menu extends AbstractHelper
{
    /**
     * @return string
     */
    public function render()
    {
        $filter = $this->navigationPluginManager->build($this->container);
        foreach ($this->filter as $filterName => $filterOptions) {
            $filterOptions['container'] = $filter;
            $filter = $this->filterPluginManager->build($filterName, $filterOptions);
        }

        $iterator = new \RecursiveIteratorIterator($filter, \RecursiveIteratorIterator::SELF_FIRST);
        $iterator->setMaxDepth($this->maxDepth);

        foreach ($iterator as $page) {
        }
        $iterator->rewind();

        $model = [
            'iterator' => $iterator,
            'minDepth' => $this->minDepth,
        ];

        $html = $this->view->render($this->partial, $model);
        $this->reset();

        return $html;
    }
}
