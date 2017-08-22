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
