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

namespace Core42\Model;

interface NestedSetInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getParentId();

    /**
     * @return string
     */
    public function getParentIdFieldName();

    /**
     * @param int $nestedLeft
     * @return $this
     */
    public function setNestedLeft($nestedLeft);

    /**
     * @return int
     */
    public function getNestedLeft();

    /**
     * @return string
     */
    public function getNestedLeftFieldName();

    /**
     * @param int $nestedRight
     * @return $this
     */
    public function setNestedRight($nestedRight);

    /**
     * @return int
     */
    public function getNestedRight();

    /**
     * @return string
     */
    public function getNestedRightFieldName();
}
