<?php
/**
 * core42 (www.raum42.at)
 *
 * @link http://www.raum42.at
 * @copyright Copyright (c) 2010-2014 raum42 OG (http://www.raum42.at)
 *
 */

namespace Core42\Db\Adapter\Profiler;

use Zend\Db\Adapter\Profiler\Profiler;
use Zend\Log\Logger;

class LoggingProfiler extends Profiler
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string|\Zend\Db\Adapter\StatementContainerInterface $target
     * @return Profiler
     */
    public function profilerStart($target)
    {
        return parent::profilerStart($target);
    }

    /**
     * @return Profiler
     */
    public function profilerFinish()
    {
        $profiler = parent::profilerFinish();
        $this->logger->info(var_export($this->getLastProfile(), true));

        return $profiler;
    }
}
