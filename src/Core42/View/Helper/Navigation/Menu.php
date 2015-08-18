<?php
namespace Core42\View\Helper\Navigation;

use Core42\Navigation\Filter\IsAllowedFilter;

class Menu extends AbstractHelper
{
    /**
     * @return string
     */
    public function render()
    {
        $filter = new IsAllowedFilter($this->getContainer($this->container), $this->navigation);
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
