<?php

/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
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
