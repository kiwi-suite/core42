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

namespace Core42\Command;

interface CommandInterface
{
    /**
     * @return CommandInterface
     */
    public function run();

    /**
     * @param bool $dryRun
     * @return CommandInterface
     */
    public function setDryRun($dryRun);

    /**
     * @param bool $enable
     * @return CommandInterface
     */
    public function enableThrowExceptions($enable);

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @return bool
     */
    public function hasErrors();

    /**
     * @param array $values
     */
    public function hydrate(array $values);
}
