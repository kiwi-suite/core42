<?php

/*
 * core42
 *
 * @package core42
 * @link https://github.com/kiwi-suite/core42
 * @copyright Copyright (c) 2010 - 2017 kiwi suite (https://www.kiwi-suite.com)
 * @license MIT License
 * @author kiwi suite <dev@kiwi-suite.com>
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
