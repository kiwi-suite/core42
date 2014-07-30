<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Queue;

interface QueueAwareInterface
{
    /**
     * @param array $params
     * @return mixed
     */
    public function queueSetup(array $params);

    /**
     * @return Job
     */
    public function queueExtract();
}
