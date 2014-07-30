<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Queue\Adapter;

use Core42\Queue\Job;

interface AdapterInterface
{
    /**
     * @param array $options
     */
    public function setOptions(array $options = array());

    /**
     * @param Job $job
     */
    public function push(Job $job);

    /**
     * @return Job|null
     */
    public function pop();

    /**
     * @return int
     */
    public function count();
}
