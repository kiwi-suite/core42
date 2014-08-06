<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Command;

interface CommandInterface
{
    /**
     * @return CommandInterface
     */
    public function run();

    /**
     * @param boolean $dryRun
     * @return CommandInterface
     */
    public function setDryRun($dryRun);

    /**
     * @param boolean $enable
     * @return CommandInterface
     */
    public function enableThrowExceptions($enable);

    /**
     * @return array
     */
    public function getErrors();

    /**
     * @return boolean
     */
    public function hasErrors();

    /**
     * @param array $values
     */
    public function hydrate(array $values);
}
