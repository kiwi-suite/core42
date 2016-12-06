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
