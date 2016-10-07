<?php
namespace Core42\Navigation;

use Core42\Navigation\Page\PageInterface;
use  RecursiveIterator;

interface ContainerInterface extends RecursiveIterator
{
    /**
     * @param PageInterface $page
     */
    public function addPage(PageInterface $page);

    /**
     * @param PageInterface $page
     * @return
     */
    public function removePage(PageInterface $page);

    /**
     *
     */
    public function sort();
}
