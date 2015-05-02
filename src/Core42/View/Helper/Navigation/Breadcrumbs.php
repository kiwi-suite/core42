<?php
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
            'navigation' => $this->navigation,
        ];

        return $this->view->render($this->partial, $model);
    }
}
